<?php

namespace Grimm\Controller;

class DeployController extends \Controller {

    public function github()
    {
        if(\Input::get('github_secret') !== getenv('GITHUB_SECRET')) {
            return \Response::json(['message' => 'invalid token'], 500);
        }

        $payload = (object)json_decode(\Input::get('payload'));

        if(!is_object($payload)) {
            return \Response::json(['message' => 'invalid payload'], 500);
        }

        $ref = isset($payload->ref) ? $payload->ref : '';

        //log the request
        \Log::info(print_r($payload, TRUE));

        if ($ref === 'refs/heads/' . getenv('GIT_BRANCH')) {
            \Log::info(exec('./build.sh'));
            return \Response::json(['message' => 'finished'], 200);
        }
    }

}
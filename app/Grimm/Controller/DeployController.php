<?php

namespace Grimm\Controller;

class DeployController extends \Controller {

    public function github()
    {
        if(\Input::get('github_secret') !== getenv('GITHUB_SECRET')) {
            return \Response::json(['message' => 'invalid token'], 500);
        }

        $payload = (object)\Input::json()->all();

        if(!is_object($payload)) {
            return \Response::json(['message' => 'invalid payload'], 500);
        }

        $ref = isset($payload->ref) ? $payload->ref : '';

        if ($ref === 'refs/heads/' . getenv('GIT_BRANCH')) {
            // path to your site deployment script
            echo base_path('build.sh');
            ob_start();
            passthru(base_path('build.sh'), $res);
            $var = ob_get_contents();
            ob_end_clean();

            return \Response::json(['message' => $var, 'code' => $res], 200);
        } else {
            return \Response::json(['message' => 'not my branch'], 200);
        }
    }

}
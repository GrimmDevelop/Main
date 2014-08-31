<?php

namespace Grimm\Controller\Api;

use Sentry;
use Queue;
use Input;

class ImportController extends \Controller {

    protected $queue;

    public function startLetterImport() {
        if(!(Sentry::check() && Sentry::getUser()->hasAccess('import.letters'))) {
            return \App::make('grimm.unauthorized');
        }

        Queue::push('Grimm\Controller\Queue\Letter@importLetters', array('source' => Input::get('data')));

        return \Response::json(array('success' => array('message' => 'Start importing letters.')));
    }

    public function startLocationImport() {
        if(!(Sentry::check() && Sentry::getUser()->hasAccess('import.locations'))) {
            return \App::make('grimm.unauthorized');
        }

        // start locations import

        return \Response::json(array('success' => array('message' => 'Start importing geo locations.')));
    }

}

<?php

namespace Grimm\Controller\Api;

use Sentry;
use Queue;
use Input;

class ImportController extends \Controller
{

    protected $queue;

    public function startLetterImport()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('import.letters'))) {
            return \App::make('grimm.unauthorized');
        }

        Queue::push('Grimm\Controller\Queue\Letter@importLetters', array('source' => Input::get('data')));

        return \Response::json(array('success' => array('message' => 'Start importing letters.')));
    }

    public function startLocationImport()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('import.locations'))) {
            return \App::make('grimm.unauthorized');
        }

        Queue::push('Grimm\Controller\Queue\Location@import', array('source' => Input::get('data')));

        return \Response::json(array('success' => array('message' => 'Start importing geo locations.')));
    }

    public function startPersonImport()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('import.letters'))) {
            return \App::make('grimm.unauthorized');
        }

        Queue::push('Grimm\Controller\Queue\Person@import', array('source' => Input::get('data')));

        return \Response::json(array('success' => array('message' => 'Start importing persons.')));
    }

}

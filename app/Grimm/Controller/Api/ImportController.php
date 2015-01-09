<?php

namespace Grimm\Controller\Api;

use Queue;
use Input;

class ImportController extends \Controller {

    protected $queue;

    public function startLetterImport()
    {
        Queue::push('Grimm\Controller\Queue\Letter@importLetters', array('source' => Input::get('data')));

        return \Response::json(array('success' => array('message' => 'Start importing letters.')));
    }

    public function startLocationImport()
    {
        Queue::push('Grimm\Controller\Queue\Location@import', array('source' => Input::get('data')));

        return \Response::json(array('success' => array('message' => 'Start importing geo locations.')));
    }

    public function startPersonImport()
    {
        Queue::push('Grimm\Controller\Queue\Person@import', array('source' => Input::get('data')));

        return \Response::json(array('success' => array('message' => 'Start importing persons.')));
    }

}

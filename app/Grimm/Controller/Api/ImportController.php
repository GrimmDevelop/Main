<?php

namespace Grimm\Controller\Api;

use Grimm\Queue\QueueJobManager;
use Queue;
use Input;
use Response;

class ImportController extends \Controller {

    protected $queueJobManager;

    function __construct(QueueJobManager $queue)
    {
        $this->queueJobManager = $queue;
    }


    public function startLetterImport()
    {
        $data = ['source' => Input::get('data')];
        $handler = 'Grimm\Controller\Queue\Letter@importLetters';
        $token = $this->queueJobManager->issue('Import Letters', $handler, $data);
        //Queue::push('Grimm\Controller\Queue\Letter@importLetters', array('source' => Input::get('data')));

        return Response::json(array('success' => array('message' => 'Start importing letters. Job-ID: ' . $token)));
    }

    public function startLocationImport()
    {
        Queue::push('Grimm\Controller\Queue\Location@import', array('source' => Input::get('data')));

        return Response::json(array('success' => array('message' => 'Start importing geo locations.')));
    }

    public function startPersonImport()
    {
        Queue::push('Grimm\Controller\Queue\Person@import', array('source' => Input::get('data')));

        return Response::json(array('success' => array('message' => 'Start importing persons.')));
    }

}

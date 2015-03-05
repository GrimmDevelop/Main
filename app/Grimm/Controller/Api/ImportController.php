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
        $handler = 'Grimm\Controller\Queue\Letter';
        $token = $this->queueJobManager->issue('Import Letters', $handler, $data);

        return Response::json(array('success' => array('message' => 'Start importing letters. Job-ID: ' . $token)));
    }

    public function startLocationImport()
    {
        $data = ['source' => Input::get('data')];
        $handler = 'Grimm\Controller\Queue\Location';
        $token = $this->queueJobManager->issue('Import Locations from ' . basename($data['source']), $handler, $data);
        //Queue::push('Grimm\Controller\Queue\Location@import', array('source' => Input::get('data')));

        return Response::json(array('success' => array('message' => 'Start importing geo locations. Job-ID: ' . $token)));
    }

    public function startPersonImport()
    {
        $data = ['source' => Input::get('data')];
        $handler = 'Grimm\Controller\Queue\Person';
        $token = $this->queueJobManager->issue('Import Persons from ' . basename($data['source']), $handler, $data);
        //Queue::push('Grimm\Controller\Queue\Person@import', array('source' => Input::get('data')));

        return Response::json(array('success' => array('message' => 'Start importing persons. Job-ID: ' . $token)));
    }

}

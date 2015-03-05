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
        $handler = 'Grimm\Queue\Jobs\Letter';
        $token = $this->queueJobManager->issue('Import Letters', $handler, $data);

        return Response::json(array('success' => array('message' => 'Start importing letters. Job-ID: ' . $token)));
    }

    public function startLocationImport()
    {
        $data = ['source' => Input::get('data')];
        $handler = 'Grimm\Queue\Jobs\Location';
        $token = $this->queueJobManager->issue('Import Locations from ' . basename($data['source']), $handler, $data);

        return Response::json(array('success' => array('message' => 'Start importing geo locations. Job-ID: ' . $token)));
    }

    public function startPersonImport()
    {
        $data = ['source' => Input::get('data')];
        $handler = 'Grimm\Queue\Jobs\Person';
        $token = $this->queueJobManager->issue('Import Persons from ' . basename($data['source']), $handler, $data);

        return Response::json(array('success' => array('message' => 'Start importing persons. Job-ID: ' . $token)));
    }

}

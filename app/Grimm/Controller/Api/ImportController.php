<?php

namespace Grimm\Controller\Api;

use Grimm\Queue\QueueJobManager;
use Queue;
use Input;
use Response;
use Sentry;

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
        $token = $this->queueJobManager->issue('Import Letters', $handler, $data, Sentry::getUser()->id);

        return Response::json(array('success' => array('message' => 'Start importing letters. Job-ID: ' . $token)));
    }

    public function startLocationImport()
    {
        $data = ['source' => Input::get('data')];
        $handler = 'Grimm\Queue\Jobs\Location';
        $title = 'Import Locations from ' . basename($data['source']);
        $token = $this->queueJobManager->issue($title, $handler, $data, Sentry::getUser()->id);

        return Response::json(array('success' => array('message' => 'Start importing geo locations. Job-ID: ' . $token)));
    }

    public function startPersonImport()
    {
        $data = ['source' => Input::get('data')];
        $handler = 'Grimm\Queue\Jobs\Person';
        $title = 'Import Persons from ' . basename($data['source']);
        $token = $this->queueJobManager->issue($title, $handler, $data, Sentry::getUser()->id);

        return Response::json(array('success' => array('message' => 'Start importing persons. Job-ID: ' . $token)));
    }

}

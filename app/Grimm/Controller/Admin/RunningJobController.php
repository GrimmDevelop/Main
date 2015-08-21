<?php


namespace Grimm\Controller\Admin;


use Grimm\OutputTransformer\ArrayOutput;
use Grimm\Queue\JobStatus;
use Response;

class RunningJobController extends \Controller {


    /**
     * @var ArrayOutput
     */
    private $arrayOutput;

    public function __construct(ArrayOutput $arrayOutput)
    {
        $this->arrayOutput = $arrayOutput;
    }

    public function getTasks()
    {
        return Response::json($this->arrayOutput->transform(JobStatus::with('starter')->where('status', '<', 3)->get()->toArray()));
    }
}
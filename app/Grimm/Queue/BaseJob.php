<?php


namespace Grimm\Queue;


abstract class BaseJob
{
    private $token;

    /**
     * @var QueueJobManager
     */
    protected $queueJobManager;

    public function __construct(QueueJobManager $queueJobManager)
    {
        $this->queueJobManager = $queueJobManager;
    }

    /**
     * Code to run before setting the state to running (e.g. validity checking of data)
     * @return mixed
     */
    public function preflight($job, $data)
    {

    }

    /**
     * The actual job code
     * @param $job
     * @param $data
     * @return mixed
     */
    public abstract function run($job, $data);

    /**
     * Code to be run after running the job (e.g. determining if the job should be retained or is finishing)
     * @return mixed
     */
    public function postflight($job, $data)
    {

    }

    public function fire($job, $data)
    {
        if (!array_key_exists('queue_job_token', $data)) {
            throw new QueueTokenNotFoundException('The Queue Token for this job was not found!');
        }

        $this->token = $token = $data['queue_job_token'];

        $this->preflight($job, $data);

        $this->queueJobManager->running($this->token);

        $this->run($job, $data);

        $this->postflight($job, $data);
    }

    public function progress($message, $percentage)
    {
        if ($this->token !== null) {
            $this->queueJobManager->reportProgress($this->token, $message, $percentage);
        }
    }

    public function retain($data, $handler = null)
    {
        if ($this->token !== null) {
            $theHandler = ($handler === null) ? static::class : $handler;

            $this->queueJobManager->retain($this->token, $theHandler, $data);
        }
    }

    public function finish()
    {
        $this->queueJobManager->finish($this->token);
    }
}
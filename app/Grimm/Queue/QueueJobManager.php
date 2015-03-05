<?php


namespace Grimm\Queue;


use Queue;

class QueueJobManager {

    /**
     * This will push a new job on the queue
     */
    public function issue($title, $handler, $data)
    {
        $token = $this->generateJobId($handler);
        JobStatus::create([
            'title' => $title,
            'token' => $token,
            'handler' => $handler,
            'progress' => [],
            'status'   => 0
        ]);
        $data['queue_job_token'] = $token;
        Queue::push($handler, $data);

        return $token;
    }

    /**
     * This will enqueue an existing job again to make space
     * for other jobs and to avoid timeouts
     */
    public function retain($token, $handler, $data)
    {
        $job = JobStatus::where('token', $token)->first();
        $job->makeRetained();
        $job->handler = $handler;
        $job->save();
        Queue::push($handler, $data);
    }

    /**
     * This will mark a job as running
     * @param $token
     */
    public function running($token)
    {
        $job = JobStatus::where('token', $token)->first();
        $job->makeRunning();
        $job->save();
    }

    /**
     * This is used by the jobs to report progress that can be displayed in the UI
     */
    public function reportProgress($token, $progress)
    {
        $job = JobStatus::where('token', $token)->first();

        $job->addProgressMessage($progress);
        $job->save();
    }

    public function finish($token)
    {
        $job = JobStatus::where('token', $token)->first();
        $job->makeFinished();
        $job->save();
    }

    protected function generateJobId($handler)
    {
        return substr(time() . md5($handler), 0, 60);
    }
}
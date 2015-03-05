<?php


namespace Grimm\Queue;


class QueueStatusService {

    public function getTasks()
    {
        return JobStatus::all();
    }
}
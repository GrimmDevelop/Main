<?php

namespace Grimm;

class Queue {
    
    public function fire($job, $data) {
        file_put_contents(__DIR__ . "/test", $job->getJobId() . "\n\n" . json_encode($data));

        $job->delete();
    }

}

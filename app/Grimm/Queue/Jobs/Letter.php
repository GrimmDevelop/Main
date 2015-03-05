<?php

namespace Grimm\Queue\Jobs;

use Grimm\Converter\Letter as Converter;
use Grimm\Import\Persistence\RecordPersistence;
use Grimm\Models\Letter as Model;
use Grimm\Queue\BaseJob;
use Grimm\Queue\QueueJobManager;

class Letter extends BaseJob {

    use DBFImportJob;

    public function __construct(Converter $converter, QueueJobManager $queueJobManager, RecordPersistence $recordPersistence)
    {
        parent::__construct($queueJobManager);
        $this->converter = $converter;
        $this->recordPersistence = $recordPersistence;
    }

    /**
     * The actual job code
     * @param $job
     * @param $data
     * @return mixed
     */
    public function run($job, $data)
    {
        $this->importRecords($data);
    }
}


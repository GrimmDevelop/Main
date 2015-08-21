<?php


namespace Grimm\Queue\Jobs;


use Grimm\Contract\Converter;
use Grimm\Import\Persistence\RecordPersistence;

trait DBFImportJob {

    protected $last;
    protected $totalRows;
    protected $nextEnd;

    /**
     * @var Converter
     */
    protected $converter;
    /**
     * @var RecordPersistence
     */
    protected $recordPersistence;

    /**
     * @param $data
     * @return array
     * @throws \Exception
     */
    protected function importRecords($data)
    {

        \Eloquent::unguard();

        foreach ($this->converter->parse() as $record) {
            if ($this->last >= $this->nextEnd) {
                break;
            }

            if ($letter = $this->recordPersistence->persist($record)) {
                // letter created and/or updated
            }

            $this->last++;
        }

        \Eloquent::reguard();
    }

    public function preflight($job, $data)
    {
        if (!isset($data['source']) || !file_exists(storage_path('upload') . $data['source']))
        {
            throw new \InvalidArgumentException('Cannot find source file ' . storage_path('upload') . $data['source']);
        }

        $this->converter->setSource(storage_path('upload') . $data['source']);

        // Check if we have to start later because this is another iteration for timeout protection
        $this->last = 0;
        if (array_key_exists('last', $data) && $data['last'] > 0) {
            $this->converter->skipTo($data['last']);
            $this->last = $data['last'];
            $this->progress('Looked at ' . $this->last . ' Entries', round($this->last / $this->converter->total() * 100));
        } else {
            $this->progress('Start processing job for ' . $data['source'], 0);
        }

        $this->totalRows = $this->converter->totalEntries();

        // Process 1000 items per job
        // TODO: make this somehow configurable
        $this->nextEnd = $this->last + 500;
    }

    public function postflight($job, $data)
    {
        if ($this->nextEnd < $this->totalRows) {
            $data['last'] = $this->nextEnd;
            $this->retain($data);
        } else {
            $this->finish();
        }

        $job->delete();
    }

}
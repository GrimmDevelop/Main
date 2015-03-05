<?php

namespace Grimm\Controller\Queue;

use Grimm\Converter\Letter as Converter;
use Grimm\Models\Letter as Model;
use Grimm\Models\Letter\Information;
use Grimm\Queue\BaseJob;
use Grimm\Queue\QueueJobManager;

class Letter extends BaseJob {
    protected $last;
    protected $totalRows;
    protected $nextEnd;

    /**
     * @var Converter
     */
    private $converter;

    public function __construct(Converter $converter, QueueJobManager $queueJobManager)
    {
        parent::__construct($queueJobManager);
        $this->converter = $converter;
    }

    public function firstOrCreateAndUpdate($record)
    {
        $letter = Model::find($record['id']);

        if ($letter == null)
        {
            $letter = Model::create(array(
                'id'       => $record['id'],
                'code'     => $record['code'],
                'language' => $record['language'],
                'date'     => $record['date']
            ));
        } else
        {
            $letter->code = $record['code'];
            $letter->language = $record['language'];
            $letter->date = $record['date'];
            $letter->save();
        }

        $letter->information()->delete();

        foreach ($record['information'] as $index => $value)
        {
            $this->attachInfoToLetter($letter, $index, $value);
        }

        return $letter;
    }

    protected function attachInfoToLetter($letter, $code, $data)
    {
        if (is_array($data))
        {
            foreach ($data as $item)
            {
                $this->attachInfoToLetter($letter, $code, $item);
            }
        } else
        {
            $letter->information()->save(new Information(array(
                'code' => $code,
                'data' => $data
            )));
        }
    }

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

            if ($letter = $this->firstOrCreateAndUpdate($record)) {
                // letter created and/or updated
            }

            $this->last++;
        }

        \Eloquent::reguard();
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
            $this->progress('Looked at ' . $this->last . ' Entries');
        } else {
            $this->progress('Start processing letter import for ' . $data['source']);
        }

        $this->totalRows = $this->converter->totalEntries();

        // Process 1000 items per job
        // TODO: make this somehow configurable
        $this->nextEnd = $this->last + 1000;
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


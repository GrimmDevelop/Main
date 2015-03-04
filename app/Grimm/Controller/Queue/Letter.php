<?php

namespace Grimm\Controller\Queue;

use Grimm\Converter\Letter as Converter;
use Grimm\Models\Letter as Model;
use Grimm\Models\Letter\Information;
use Grimm\Queue\QueueJobManager;
use Queue;

class Letter {

    /**
     * @var Converter
     */
    private $converter;
    /**
     * @var QueueJobManager
     */
    private $queueJobManager;

    public function __construct(Converter $converter, QueueJobManager $queueJobManager)
    {
        $this->converter = $converter;
        $this->queueJobManager = $queueJobManager;
    }

    public function importLetters($job, $data)
    {
        if (!isset($data['source']) || !file_exists(storage_path('upload') . $data['source']))
        {
            throw new \InvalidArgumentException('Cannot find source file ' . storage_path('upload') . $data['source']);
        }

        $this->converter->setSource(storage_path('upload') . $data['source']);

        $token = $data['queue_job_token'];

        $this->queueJobManager->running($token);

        list($totalRows, $last, $nextEnd) = $this->importRecords($data);

        $this->queueJobManager->reportProgress($token, 'Imported ' . $last . ' Entries');

        if ($nextEnd < $totalRows) {
            $data['last'] = $nextEnd;
            $this->queueJobManager->retain($token, static::class . '@importLetters', $data);
        } else {
            $this->queueJobManager->finish($token);
        }

        \Eloquent::reguard();

        $job->delete();
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
        $totalRows = $this->converter->totalEntries();

        // Check if we have to start later because this is another iteration for timeout protection
        $last = 0;
        if (array_key_exists('last', $data) && $data['last'] > 0) {
            $this->converter->skipTo($data['last']);
            $last = $data['last'];
        }

        // Process 1000 items per job
        // TODO: make this somehow configurable
        $nextEnd = $last + 1000;

        \Eloquent::unguard();

        foreach ($this->converter->parse() as $record) {
            if ($last >= $nextEnd) {
                break;
            }

            if ($letter = $this->firstOrCreateAndUpdate($record)) {
                // letter created and/or updated
            }

            $last++;
        }
        return array($totalRows, $last, $nextEnd);
    }
}


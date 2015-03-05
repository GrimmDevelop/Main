<?php

namespace Grimm\Controller\Queue;

use Grimm\Converter\Person as Converter;
use Grimm\Models\Person as Model;
use Grimm\Models\Person\Information;
use Grimm\Queue\BaseJob;
use Grimm\Queue\QueueJobManager;

class Person extends BaseJob {

    protected $last;
    protected $nextEnd;
    protected $totalRows;

    public function __construct(Converter $converter, QueueJobManager $queueJobManager)
    {
        parent::__construct($queueJobManager);
        $this->converter = $converter;
    }

    public function import($job, $data)
    {

        \Eloquent::unguard();

        foreach ($this->converter->parse() as $record)
        {
            if ($this->last >= $this->nextEnd) {
                break;
            }
            if ($person = $this->firstOrCreateAndUpdate($record))
            {
                // person created and/or updated
            }
            $this->last++;
        }

        \Eloquent::reguard();
    }

    protected function firstOrCreateAndUpdate($record)
    {
        $person = Model::where('name_2013', $record['name_2013'])->first();

        if ($person == null)
        {
            $person = Model::create(array(
                'name_2013' => $record['name_2013']
            ));
        } else
        {
            $person->name_2013 = $record['name_2013'];
            $person->save();
        }

        $person->information()->delete();

        foreach ($record['information'] as $index => $value)
        {
            $this->attachInfoToPerson($person, $index, $value);
        }

        return $person;
    }

    protected function attachInfoToPerson($person, $code, $data)
    {
        if (is_array($data))
        {
            foreach ($data as $item)
            {
                $this->attachInfoToPerson($person, $code, $item);
            }
        } else
        {
            $person->information()->save(new Information(array(
                'code' => $code,
                'data' => $data
            )));
        }
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
            $this->progress('Start processing person import for ' . $data['source']);
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

    /**
     * The actual job code
     * @param $job
     * @param $data
     * @return mixed
     */
    public function run($job, $data)
    {
        $this->import($job, $data);
    }
}

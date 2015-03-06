<?php

namespace Grimm\Queue\Jobs;

use Grimm\Models\Location as Import;
use Grimm\Converter\Location as Converter;
use Grimm\Queue\BaseJob;
use Grimm\Queue\QueueJobManager;
use Illuminate\Database\QueryException;

class Location extends BaseJob
{
    protected $last;
    protected $limit = 1000;
    protected $total = null;

    /**
     * @var Converter
     */
    private $converter;

    public function __construct(Converter $converter, QueueJobManager $queueJobManager)
    {
        parent::__construct($queueJobManager);
        $this->converter = $converter;
    }

    public function import($job, $data)
    {
        \Eloquent::unguard();

        foreach ($this->converter->parse() as $record) {
            if ($location = $this->firstOrCreate($record)) {
                // echo $record['id'] . "\n";
            }
        }

        \Eloquent::reguard();
    }

    public function firstOrCreate($record)
    {
        try {
            return Import::firstOrCreate($record);
        } catch (QueryException $e) {
            echo $e->getMessage() . "\n";
            echo $e->getSql() . "\n";
            print_r($e->getBindings());
        }
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

    public function preflight($job, $data)
    {
        if (!isset($data['source']) || !file_exists(storage_path('upload') . $data['source'])) {
            throw new \InvalidArgumentException('Cannot find source file ' . storage_path('upload') . $data['source']);
        }

        $this->converter->setSource(storage_path('upload') . $data['source']);

        // Check if we have to start later because this is another iteration for timeout protection
        $this->last = 0;
        if (array_key_exists('last', $data) && $data['last'] > 0) {
            $this->converter->skipTo($data['last']);
            $this->last = $data['last'];
            $this->progress('Imported ' . $this->last . ' Locations', round($this->last / $this->converter->total() * 100));
        } else {
            $this->progress('Start processing location import for ' . $data['source'], 0);
        }

        $this->converter->setLimit($this->limit);
    }

    public function postflight($job, $data)
    {
        if ($this->converter->isFinished()) {
            $this->finish();
            $job->delete();
        } else {
            $data['last'] = $this->last + $this->limit;
            $this->retain($data);
            $job->delete();
        }
    }
}
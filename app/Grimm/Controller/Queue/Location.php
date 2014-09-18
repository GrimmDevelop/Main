<?php

namespace Grimm\Controller\Queue;

use Grimm\Models\Location as Import;
use Grimm\Converter\Location as Converter;

class Location extends \Controller
{

    /**
     * @var Converter
     */
    private $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function import($job, $data)
    {
        if (!isset($data['source']) || !file_exists(storage_path('upload') . $data['source'])) {
            throw new \InvalidArgumentException('Cannot find source file ' . storage_path('upload') . $data['source']);
        }

        $this->converter->setSource(storage_path('upload') . $data['source']);

        \Eloquent::unguard();

        foreach ($this->converter->parse() as $record) {
            if ($location = $this->firstOrCreate($record)) {
                // echo $record['id'] . "\n";
            }
        }

        \Eloquent::reguard();

        $job->delete();
    }

    public function firstOrCreate($record)
    {
        return Import::firstOrCreate($record);
    }
} 
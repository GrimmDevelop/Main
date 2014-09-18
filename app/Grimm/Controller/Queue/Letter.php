<?php

namespace Grimm\Controller\Queue;

use Grimm\Converter\Letter as Converter;
use Grimm\Models\Letter\Import;

class Letter extends \Controller
{

    /**
     * @var Converter
     */
    private $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function importLetters($job, $data)
    {
        if (!isset($data['source']) || !file_exists(storage_path('upload') . $data['source'])) {
            throw new \InvalidArgumentException('Cannot find source file ' . storage_path('upload') . $data['source']);
        }

        $this->converter->setSource(storage_path('upload') . $data['source']);

        \Eloquent::unguard();

        foreach ($this->converter->parse() as $record) {
            if ($letter = $this->firstOrCreate($record)) {
                foreach ($this->compareAndUpdate($record, $letter) as $updated) {
                    // echo $updated . "\n";
                }
            }
        }

        \Eloquent::reguard();

        $job->delete();
    }

    public function compareAndUpdate($new, Import $old)
    {
        $updatedFields = array();
        /*foreach ($new as $index => $value) {
            if ($old->$index != $value) {
                $old->{$index} = $value;
                $updatedFields[] = $index;
            }
        }*/

        // Save only if something was updated
        if (count($updatedFields)) {
            $old->save();
        }

        return $updatedFields;
    }

    public function firstOrCreate($record)
    {
        if ($letter = Import::find($record->id)) {
            return $letter;
        }

        $letter = new Import();
        foreach ($record as $index => $value) {
            $letter->$index = $value;
        }
        $letter->save();

        return $letter;
    }
}


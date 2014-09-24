<?php

namespace Grimm\Controller\Queue;

use Grimm\Converter\Letter as Converter;
use Grimm\Models\Letter as Model;
use Grimm\Models\Letter\Information;

class Letter extends \Controller {

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
            if ($letter = $this->firstOrCreateAndUpdate($record)) {
                // letter created and/or updated
            }
        }

        \Eloquent::reguard();

        $job->delete();
    }

    public function firstOrCreateAndUpdate($record)
    {
        $letter = Model::find($record['id']);

        if ($letter == null) {
            $letter = Model::create(array(
                'id' => $record['id'],
                'code' => $record['code'],
                'language' => $record['sprache'],
                'date' => $record['datum']
            ));
        } else {
            $letter->code = $record['code'];
            $letter->language = $record['language'];
            $letter->date = $record['date'];
            $letter->save();
        }

        $letter->informations()->delete();

        foreach ($record['informations'] as $index => $value) {
            $this->attachInformationToLetter($letter, $index, $value);
        }

        return $letter;
    }

    protected function attachInformationToLetter($letter, $code, $data)
    {
        if (is_array($data)) {
            foreach ($data as $item) {
                $this->attachInformationToLetter($letter, $code, $item);
            }
        } else {
            $letter->informations()->save(new Information(array(
                'code' => $code,
                'data' => $data
            )));
        }
    }
}


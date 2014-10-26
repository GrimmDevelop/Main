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
        if (!isset($data['source']) || !file_exists(storage_path('upload') . $data['source']))
        {
            throw new \InvalidArgumentException('Cannot find source file ' . storage_path('upload') . $data['source']);
        }

        $this->converter->setSource(storage_path('upload') . $data['source']);

        \Eloquent::unguard();

        foreach ($this->converter->parse() as $record)
        {
            if ($letter = $this->firstOrCreateAndUpdate($record))
            {
                // letter created and/or updated
            }
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
}


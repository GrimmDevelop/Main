<?php

namespace Grimm\Import\Persistence;

use Grimm\Import\Validation\LetterValidation;
use Grimm\Models\Letter as Model;
use Grimm\Models\Letter\Information;

class LetterRecordPersistence implements RecordPersistence
{
    /**
     * @var LetterValidation
     */
    protected $letterValidation;

    public function __construct(LetterValidation $letterValidation)
    {

        $this->letterValidation = $letterValidation;
    }

    public function persist($record)
    {
        $recordInformation = $record->getInformation();
        $letter = Model::find($record->getId());

        if ($letter == null) {
            //$recordData = array_filter($record->toArray(), function($key) { return $key != 'information';}, ARRAY_FILTER_USE_KEY);
            $letter = Model::create(array(
                'id' => $record->getId(),
                'code' => $record->getCode(),
                'language' => $record->getLanguage(),
                'date' => $record->getDate(),
                'valid' => $this->letterValidation->validate($record)
            ));
        } else {
            $letter->code = $record->getCode();
            $letter->language = $record->getLanguage();
            $letter->date = $record->getDate();
            $letter->valid = $this->letterValidation->validate($record);
            $letter->save();
        }

        $letter->information()->delete();

        foreach ($recordInformation as $index => $value) {
            $this->attachInfoToLetter($letter, $index, $value);
        }

        return $letter;
    }

    protected function attachInfoToLetter($letter, $code, $data)
    {
        if (is_array($data)) {
            foreach ($data as $item) {
                $this->attachInfoToLetter($letter, $code, $item);
            }
        } else {
            $letter->information()->save(new Information(array(
                'code' => $code,
                'data' => $data
            )));
        }
    }
}
<?php

namespace Grimm\Import\Persistence;

use Grimm\Models\Letter as Model;
use Grimm\Models\Letter\Information;

class LetterRecordPersistence implements RecordPersistence
{

    public function persist($record)
    {
        $letter = Model::find($record['id']);

        if ($letter == null) {
            $letter = Model::create(array(
                'id' => $record['id'],
                'code' => $record['code'],
                'language' => $record['language'],
                'date' => $record['date']
            ));
        } else {
            $letter->code = $record['code'];
            $letter->language = $record['language'];
            $letter->date = $record['date'];
            $letter->save();
        }

        $letter->information()->delete();

        foreach ($record['information'] as $index => $value) {
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
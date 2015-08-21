<?php


namespace Grimm\Import\Validation;


use Grimm\Import\Records\Record;

class LetterValidation implements RecordValidation {

    public function validate($record)
    {
        $codeRegexp = '/^[0-9]{8}(\.[0-9]{1,2})?$/';

        return !!preg_match($codeRegexp, $record->getCode());
    }
}
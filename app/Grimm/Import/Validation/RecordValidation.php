<?php


namespace Grimm\Import\Validation;


use Grimm\Import\Records\Record;

interface RecordValidation {
    /**
     * Validates the given record, returning true if valid, false otherwise!
     * @param $record
     * @return bool
     */
    public function validate($record);
}
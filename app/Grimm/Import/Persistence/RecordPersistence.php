<?php

namespace Grimm\Import\Persistence;


interface RecordPersistence {
    public function persist($record);
}
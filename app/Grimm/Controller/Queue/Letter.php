<?php

namespace Grimm\Controller\Queue;

use GrimmTools\LetterParser\Parser;
use Grimm\Models\Letter\Import;

class Letter extends \Controller {

    public function importLetters($job, $data) {
        if(!isset($data['source']) || !file_exists(storage_path('upload') . $data['source'])) {
            throw new \InvalidArgumentException('Cannot find source file' . storage_path('upload') . $data['source']);
        }

        Parser::setSource(storage_path('upload') . $data['source']);

        \Eloquent::unguard();

        foreach(Parser::parse() as $record) {
            if($letter = Import::find($record['nr'])) {
                if($updated = $this->updateLetter($record, $letter)) {
                    // success
                } else {
                    // error
                }
            } else {
                if($letter = $this->createLetter($record)) {
                    // success
                } else {
                    // error
                }
            }
        }

        \Eloquent::reguard();

        $job->delete();
    }

    public function updateLetter($record, Import $letter) {
        foreach($record as $index => $value) {
            $old = $letter->{$index};
            
            if($index != 'nr' && $old != $value) {
                $letter->{$index} = floatval($value);
                echo "$index: $old -> $value\n";
            }
        }
        
        return $letter->save();
    }

    public function createLetter($record) {
        $record['id'] = $record['nr'];
        unset($record['nr']);
        return Import::create($record);
    }
}


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
            if($record['nr'] == '') {
                continue;
            }

            echo $record['nr'] . "\n";
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
            $value = $index == 'code' ? floatval(str_replace(',', '.', $value)) : $value;
            
            if($index != 'nr' && $old != $value) {
                $letter->{$index} = $value;
                echo "$index: $old -> $value\n";
            }
        }
        
        return $letter->save();
    }

    public function createLetter($record) {
        $record['id'] = $record['nr'];
        unset($record['nr']);
        $record['code'] = floatval(str_replace(',', '.', $record['code']));
        return Import::create($record);
    }
}


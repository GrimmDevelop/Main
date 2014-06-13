<?php

namespace Grimm\Controller\Queue;

use GrimmTools\LetterParser\Parser;

class Letter {

    protected $parser;

    public function __construct(Parser $parser) {
        $this->parser = $parser;
    }

    public function importLetters($job, $data) {
        if(!isset($data['source']) || !file_exists($data['source'])) {
            throw new \InvalidArgumentException('Cannot find source file');
        }

        $this->parser->setSource($data['source']);

        foreach($this->parser->parse() as $row) {
            print_r($row);
        }

        $job->delete();
    }
}


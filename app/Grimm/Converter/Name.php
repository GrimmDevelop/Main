<?php

namespace Grimm\Converter;

use Psr\Log\InvalidArgumentException;

class Name {

    protected $names = null;
    protected $converted = null;

    protected $splitData;

    public function __construct()
    {

    }

    public function setNames($names)
    {
        $this->names = $names;
    }

    public function getNames()
    {
        return $this->names;
    }

    public function getConverted()
    {
        if ($this->converted == null) {
            $this->convert();
        }
        return $this->converted;
    }

    protected function convert()
    {
        if ($this->name === null) {
            throw new InvalidArgumentException();
        }

        $this->converted = [];

        $this->splitNames();

        foreach ($this->splitData as $name) {
            $this->convertName($name);
        }
    }

    protected function splitNames()
    {
        $this->splitData = array_filter(array_map('trim', explode(';', $this->names)));
    }

    public function convertName($name)
    {
        $nameSet = [
            'lastname' => '',
            'firstname' => '',
            'birthname' => '',
            'in_name_of' => '',
            'widowed' => ''
        ];

        $name = preg_replace("/;([\\/~><])/", ":$1", $name);

        // remove -(...) and -[...]
        $name = preg_replace("/(.*)\\-\\(.*?\\)(.*)/", "$1$2", $name);
        $name = preg_replace("/(.*)\\-\\[.*?\\](.*)/", "$1$2", $name);

        // remove (:>...) and (:>...)
        $name = preg_replace("/(.*)\\(\\??:[\\/~><].*?\\)(.*)/", "$1$2", $name);

        // maybe
        $name = preg_replace("/(.*)\\(\\?(.*?)\\)(.*)/", "$1$2$3", $name);

        // remove remaining braces
        $name = preg_replace("/(.*)\\(.*?\\)(.*)/", "$1$3", $name);

        // remove double spaces
        while (strpos($name, '  ') !== false) {
            $name = str_replace('  ', ' ', $name);
        }

        return trim($name);
    }
}

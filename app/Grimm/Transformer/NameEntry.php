<?php

namespace Grimm\Transformer;

use Grimm\Contract\EntryTransformer;

class NameEntry implements EntryTransformer {

    public function transform($names)
    {
        $transformedNames = [];

        foreach ($this->splitNames($names) as $name) {
            $transformedNames[] = $this->extractData($name);
        }

        return $transformedNames;
    }

    protected function splitNames($names)
    {
        return array_filter(array_map('trim', explode(';', $names)));
    }

    public function extractData($stringName)
    {
        $nameSet = [
            'last_name' => null,
            'first_name' => null,
            'birth_name' => null,
            'widowed' => null,
            'in_name_of' => null,
            'as_member_of' => null,
        ];

        $data = array_filter(array_map('trim', explode(',', $stringName)));

        $nameSet['last_name'] = $data[0];
        $nameSet['first_name'] = isset($data[1]) ? $data[1] : null;

        return array_filter($nameSet);

        $name = preg_replace("/;([\\/~><])/", ":$1", $stringName);

        // remove -(...) and -[...]
        $name = preg_replace("/(.*)\\-\\(.*?\\)(.*)/", "$1$2", $name);
        $name = preg_replace("/(.*)\\-\\[.*?\\](.*)/", "$1$2", $name);

        // remove (:>...) and [:>...]
        $name = preg_replace("/(.*)\\(\\??:[\\/~><].*?\\)(.*)/", "$1$2", $name);

        // assume maybe (?) as correct
        $name = preg_replace("/(.*)\\(\\?(.*?)\\)(.*)/", "$1$2$3", $name);

        // remove remaining braces
        $name = preg_replace("/(.*)\\((.*?)\\)(.*)/", "$1$2$3", $name);

        // remove double spaces
        while (strpos($name, '  ') !== false) {
            $name = str_replace('  ', ' ', $name);
        }

        return trim($name);
    }
}

<?php

namespace Grimm\Transformer;

use Grimm\Contract\EntryTransformer;
use Grimm\Values\NameSet;

class NameEntry implements EntryTransformer {

    /**
     * transforms name string into array of NameSets
     * @param $names
     * @return array
     */
    public function transform($names)
    {
        $transformedNames = [];

        foreach ($this->splitNames($names) as $name) {
            $transformedNames[] = $this->extractData($name);
        }

        return $transformedNames;
    }

    /**
     * Split semicolon separated names into array
     * ignores semicolons followed by "<" or ">"
     * @param $names
     * @return array
     */
    protected function splitNames($names)
    {
        return array_filter(array_map('trim', preg_split('/;(?![><])/', $names)));
    }

    /**
     *
     * @param $stringName
     * @return NameSet
     */
    public function extractData($stringName)
    {
        $data = array_filter(array_map('trim', explode(',', $stringName)));

        $last_name = $data[0];
        $first_name = isset($data[1]) ? $data[1] : null;

        return new NameSet($last_name, $first_name);

        /*
        TODO: Implement name standards

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
        */
    }
}

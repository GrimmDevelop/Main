<?php

namespace Grimm\Cluster;


use Illuminate\Support\Contracts\JsonableInterface;

class Changes implements JsonableInterface {

    protected $letters;

    protected $persons;

    public function __construct($letters, $persons) {
        $this->letters = abs((int)$letters);
        $this->persons = abs((int)$persons);
    }

    public function getLetters() {
        return $this->letters;
    }

    public function getPersons() {
        return $this->persons;
    }

    public function getTotal() {
        return $this->getLetters() + $this->getPersons();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode([
            'letters' => $this->getLetters(),
            'persons' => $this->getPersons(),
            'total' => $this->getTotal()
        ], $options);
    }
}
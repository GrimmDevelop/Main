<?php

namespace Grimm\Cluster;


use Illuminate\Support\Contracts\JsonableInterface;

class Changes implements JsonableInterface {

    protected $letters;

    protected $persons;

    protected $locations;

    public function __construct($letters, $persons, $locations) {
        $this->letters = abs((int)$letters);
        $this->persons = abs((int)$persons);
        $this->locations = abs((int)$locations);
    }

    public function getLetters() {
        return $this->letters;
    }

    public function getPersons() {
        return $this->persons;
    }

    public function getLocations() {
        return $this->locations;
    }

    public function getTotal() {
        return $this->getLetters() + $this->getPersons() + $this->getLocations();
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
            'locations' => $this->getLocations(),
            'total' => $this->getTotal()
        ], $options);
    }
}
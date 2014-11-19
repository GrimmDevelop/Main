<?php

use Grimm\Models\Person;
use Illuminate\Console\Command;

class CorrespondenceNetwork extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'correspondence:network';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'build up a correspondence network.';

    /**
     * Create a new command instance.
     *
     * @return \CorrespondenceNetwork
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $persons = Person::orderBy('name_2013')->take(30)->get();
        $persons2 = clone $persons;

        foreach($persons as $person1) {
            foreach($persons2 as $person2) {
                $this->updateRelation($person1->id, $person2->id, PHP_INT_MAX);
            }
        }

        foreach ($persons as $person) {
            foreach ($person->sendedLetters as $letter) {
                foreach ($letter->receivers as $receiver) {
                    $this->updateRelation($person->id, $receiver->id, 1);
                }
            }

            foreach ($person->receivedLetters as $letter) {
                foreach ($letter->senders as $sender) {
                    $this->updateRelation($person->id, $sender->id, 1);
                }
            }
        }

        $this->dumpRelationTable();
    }

    protected $personTable = [];

    protected function updateRelation($p1, $p2, $d)
    {
        if ($p1 == $p2) {
            return;
        }

        if(isset($this->personTable[$p2][$p1])) {
            $t = $p1;
            $p1 = $p2;
            $p2 = $t;
        }

        if (!isset($this->personTable[$p1][$p2])) {
            $this->personTable[$p1][$p2] = PHP_INT_MAX;
        }

        if ($this->personTable[$p1][$p2] > $d) {
            $this->personTable[$p1][$p2] = $d;
        }
    }

    protected function dumpRelationTable()
    {
        foreach ($this->personTable as $id1 => $data) {
            foreach ($data as $id2 => $d) {
                echo "$id1  $id2    $d\n";
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

}

<?php

use Grimm\Models\Person;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

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

    protected $displayUnknown = false;

    protected $personTable = [];

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
        $persons = Person::orderBy('name_2013')->take(abs((int)$this->option('take')))->get();
        $personsX = clone $persons;
        $personsY = clone $persons;

        foreach($personsX as $person1) {
            foreach($personsY as $person2) {
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
                if($d == PHP_INT_MAX) {
                    if($this->displayUnknown) {
                        echo str_pad($id1, 7, " ", STR_PAD_LEFT) . "  " . str_pad($id2, 7, " ", STR_PAD_LEFT) . "    unknown\n";
                    }
                } else {
                    echo str_pad($id1, 7, " ", STR_PAD_LEFT) . "  " . str_pad($id2, 7, " ", STR_PAD_LEFT) . "    $d\n";
                }
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
        return array(
            array('take', 't', InputOption::VALUE_OPTIONAL, "max rows per query", 30)
        );
    }

}

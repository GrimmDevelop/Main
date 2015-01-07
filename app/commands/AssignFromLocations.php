<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class AssignFromLocations extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'assign:from';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigns all known locations to letters (from).';

    /**
     * The controller used to assign items
     * @var AssignController
     */
    protected $controller;

    /**
     * Create a new command instance.
     *
     * @return \AssignFromLocations
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
        $take = $this->option('take') == -1 ? 9999999999999 : abs((int)$this->option('take'));
        App::make('Grimm\Controller\Admin\AssignController')->from($take);
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
            array('take', 't', InputOption::VALUE_OPTIONAL, "max rows per query", -1)
        );
    }

}

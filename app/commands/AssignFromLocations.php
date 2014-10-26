<?php

use Illuminate\Console\Command;

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
        App::make('Grimm\Controller\Admin\AssignController')->from(999999999999);
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

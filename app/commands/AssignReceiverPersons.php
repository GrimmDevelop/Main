<?php

use Illuminate\Console\Command;

class AssignReceiverPersons extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'assign:receivers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigns all known receivers to letters.';

    /**
     * Create a new command instance.
     *
     * @return \AssignReceiverPersons
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
        App::make('Grimm\Controller\Admin\AssignController')->receivers(999999999999);
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

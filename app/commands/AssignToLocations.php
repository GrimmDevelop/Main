<?php

use Illuminate\Console\Command;

class AssignToLocations extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'assign:to';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigns all known locations to letters (to).';

    /**
     * Create a new command instance.
     *
     * @return \AssignToLocations
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
        App::make('Grimm\Controller\Admin\AssignController')->to(999999999999);
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

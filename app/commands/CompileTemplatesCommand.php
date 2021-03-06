<?php

use Illuminate\Console\Command;

use Symfony\Component\Finder\Finder;

class CompileTemplatesCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'compile:templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compiles the templates for the admin area.';

    /**
     * Create a new command instance.
     *
     * @return \CompileTemplatesCommand
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
        $this->compileFrontendTemplates();
        $this->compileAdminTemplates();
    }

    protected function compileFrontendTemplates()
    {
        if (File::exists(base_path() . '/app/views/compiled.blade.php')) {
            File::delete(base_path() . '/app/views/compiled.blade.php');
        }
        $finder = new Finder();
        $views = $finder->files()->in(base_path() . '/app/views/partials')->name('*.blade.php');

        foreach ($views as $view) {
            if ($view->getRelativePath() != '') {
                $path = 'partials/' . str_replace(['/', '\\'], '.', $view->getRelativePath()) . '.' . basename($view->getFilename(), '.blade.php');
            } else {
                $path = 'partials/' . basename($view->getFilename(), '.blade.php');
            }

            // var_dump($path);
            File::append(base_path() . '/app/views/compiled.blade.php', '<script type="text/ng-template" id="' . $path . '">' . $view->getContents() . '</script>');
        }
    }

    protected function compileAdminTemplates()
    {
        if (File::exists(base_path() . '/app/views/admin/compiled.blade.php')) {
            File::delete(base_path() . '/app/views/admin/compiled.blade.php');
        }
        $finder = new Finder();
        $views = $finder->files()->in(base_path() . '/app/views/admin/partials')->name('*.blade.php');

        foreach ($views as $view) {
            if ($view->getRelativePath() != '') {
                $path = 'admin/partials/' . str_replace(['/', '\\'], '.', $view->getRelativePath()) . '.' . basename($view->getFilename(), '.blade.php');
            } else {
                $path = 'admin/partials/' . basename($view->getFilename(), '.blade.php');
            }

            // var_dump($path);
            File::append(base_path() . '/app/views/admin/compiled.blade.php', '<script type="text/ng-template" id="' . $path . '">' . $view->getContents() . '</script>');
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

<?php

namespace Grimm\Controller\Api;

use Illuminate\Filesystem\Filesystem;

class InfoController extends \Controller {

    public function __construct(Filesystem $file)
    {
        $this->file = $file;
    }

    public function version()
    {
        $changelog = '';
        if ($this->file->exists(storage_path('changelog.txt')))
        {
            $changelog = $this->file->get(storage_path('changelog.txt'));
        }

        return json_encode([
            'version'   => '0.1',
            'changelog' => $changelog
        ]);
    }

}
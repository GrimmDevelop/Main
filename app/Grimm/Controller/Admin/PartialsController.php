<?php

namespace Grimm\Controller\Admin;

class PartialsController extends \Controller {

    public function load($file)
    {
        return \View::make('admin.partials.' . $file);
    }
}

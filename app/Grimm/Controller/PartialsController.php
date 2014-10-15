<?php

namespace Grimm\Controller;

class PartialsController extends \Controller {

    public function load($file)
    {
        return \View::make('partials.' . $file);
    }
}

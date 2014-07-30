<?php

namespace Grimm\Controller\Admin;

use GrimmTools\Assets\Assets;

class AngularController extends \Controller {

    protected $module;

    public function __construct() {



    }


    protected function loadAngularController() {
        Assets::add(\URL::to('assets/js/ng-' . $this->module));
    }
}

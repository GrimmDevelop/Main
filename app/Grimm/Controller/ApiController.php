<?php

namespace Grimm\Controller;

class ApiController extends \Controller {

    public function overview() {
        return \View::make('api.overview');
    }

} 
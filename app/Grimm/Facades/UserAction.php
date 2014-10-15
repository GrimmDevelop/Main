<?php

namespace Grimm\Facades;

use Grimm\Logging\UserActionLogger;
use Illuminate\Support\Facades\Facade;

class UserAction extends Facade {

    public static function getFacadeAccessor() {
        return UserActionLogger::class;
    }

}
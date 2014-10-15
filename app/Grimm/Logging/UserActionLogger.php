<?php

namespace Grimm\Logging;

use Grimm\Models\UserActionLog;

class UserActionLogger {

    public function log($action, $data = '')
    {

        if (!is_string($data)) {
            $data = json_encode($data);
        }

        $action = UserActionLog::create([
            'user_id' => null,
            'action' => $action,
            'data' => $data
        ]);

        $action->user()->associate(\Sentry::getUser());
        $action->save();
    }
}

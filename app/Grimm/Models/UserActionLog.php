<?php

namespace Grimm\Models;

use Grimm\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserActionLog extends Model {

    protected $table = 'user_action_log';

    protected $fillable = ['action', 'data'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
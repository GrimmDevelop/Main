<?php

namespace Grimm\Models;

use Grimm\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model {
    protected $table = 'filters';

    protected $fillable = ['filter_key', 'fields', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

} 
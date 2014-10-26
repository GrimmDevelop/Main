<?php

namespace Grimm\Models;

use Grimm\Models\Person\Information;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Person extends Eloquent {

    protected $table = 'persons';

    protected $fillable = ['name_2013', 'auto_generated'];

    public function information()
    {
        return $this->hasMany(Information::class);
    }

    public function receivedLetters()
    {
        return $this->belongsToMany(Letter::class, 'letter_receiver');
    }

    public function sendedLetters()
    {
        return $this->belongsToMany(Letter::class, 'letter_sender');
    }
}

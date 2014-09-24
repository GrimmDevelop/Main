<?php

namespace Grimm\Models;

use Grimm\Models\Person\Information;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Person extends Eloquent {
    protected $table = 'persons';

    public function informations()
    {
        return $this->hasMany(Information::class);
    }

    public function receivedLetters()
    {
        return $this->belongsToMany(Letter::class, 'letters_sender');
    }

    public function sendedLetters()
    {
        return $this->belongsToMany(Letter::class, 'letters_receiver');
    }
}

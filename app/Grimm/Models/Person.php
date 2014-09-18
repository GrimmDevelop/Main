<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Person extends Eloquent
{
    protected $table = 'persons';

    public function receivedLetters()
    {
        return $this->belongsToMany('Grimmm\Models\Letter', 'letters_sender');
    }

    public function sendedLetters()
    {
        return $this->belongsToMany('Grimmm\Models\Letter', 'letters_receiver');
    }
}

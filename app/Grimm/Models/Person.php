<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Letter extends Eloquent {
    protected $table = 'letters';
    

    public function information($code = null) {
        $hasMany = $this->hasMany('Grimm\Models\Letter\Information');

        if($code !== null) {
            $hasMany->where('code', $code);
        }

        return $hasMany;
    }

    public function receivedLetters() {
        return $this->belongsToMany('Grimmm\Models\Letter', 'letters_sender');
    }
    public function sendedLetters() {
        return $this->belongsToMany('Grimmm\Models\Letter', 'letters_receiver');
    }
}

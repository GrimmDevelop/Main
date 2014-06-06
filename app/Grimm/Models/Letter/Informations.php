<?php

namespace Grimm\Models\Letter;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Grimm\Models\Letter;

class Letter extends Eloquent {
    protected $table = 'letter_informations';

    public function letter() {
        return $this->belongsTo('Grimm\Models\Letter');
    }
}

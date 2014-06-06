<?php

namespace Grimm\Models\Letter;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Grimm\Models\Letter;

class Import extends Eloquent {
    protected $table = 'letter_imports';

    public function letter() {
        return $this->belongsTo('Grimm\Models\Letter');
    }
}

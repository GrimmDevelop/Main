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

    public function sender() {
        return $this->belongsToMany('Grimmm\Models\Person', 'letters_sender');
    }
    public function receivers() {
        return $this->belongsToMany('Grimmm\Models\Person', 'letters_receiver');
    }

    public function from() {
        return $this->belongsTo('Grimm\Model\Location', 'from_id');
    }
    public function to() {
        return $this->belongsTo('Grimm\Model\Location', 'to_id');
    }
}

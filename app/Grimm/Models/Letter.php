<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Grimm\Models\Letter\Information;
use App;

class Letter extends Eloquent {
    protected $table = 'letters';
    

    public function informations($code = null) {
        $hasMany = $this->hasMany('Grimm\Models\Letter\Information');

        if($code !== null) {
            $hasMany->where('code', $code);
        }

        return $hasMany;
    }

    public function codes() {
        return App::make('Grimm\Models\Letter\Information')->codes();
    }

    public function scopeWithCodes($codes) {
        $validCodes = $this->codes();
        if(!is_array($codes)) {
            $codes = array($codes);
        }

        foreach($codes as $code) {
            if(!in_array($validCodes, $code)) {
                throw new \InvalidArgumentException('Invalid letter information code: ' . $code);
            }
        }

        return $this->with(array('informations' => function($query) use($codes) {
                $query->whereIn('code', $codes);
            }));
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

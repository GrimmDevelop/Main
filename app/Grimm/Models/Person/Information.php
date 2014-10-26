<?php

namespace Grimm\Models\Person;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Grimm\Models\Person;

class Information extends Eloquent {

    protected $table = "person_information";

    public function codes()
    {
        return array(
            'name',
            'name_alt',
            'standard',
            'wann_gepr',
            'q_standard',
            'nichtstand',
            'freigabe',
            'nichtverz',
            'druck',
            'lebt',
            'literatur',
            'biografie',
            'briefe_von',
            'briefe_an',
            'asu_j_n',
            'nl',
            'leb_orte',
            'gesehen_12',
            'herkunft'
        );
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
} 
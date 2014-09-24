<?php

namespace Grimm\Models\Person;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Grimm\Models\Person;

class Information extends Eloquent {
    protected $table = "person_informations";

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
} 
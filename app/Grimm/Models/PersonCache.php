<?php

namespace Grimm\Models;

class PersonCache extends \Eloquent {

    protected $table = "person_cache";

    protected $fillable = array('person_id', 'name');

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

}
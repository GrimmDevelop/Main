<?php

namespace Grimm\Models;

class GeoCache extends \Eloquent {

    protected $table = "geo_cache";

    protected $fillable = array('geo_id', 'name');

    public function geo()
    {
        return $this->belongsTo(Location::class);
    }

} 
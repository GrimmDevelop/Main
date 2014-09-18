<?php

namespace Grimm\Models;

class GeoCache {

    protected $table = "geo_caches";


    public function location() {
        return $this->belongsTo(Location::class);
    }

} 
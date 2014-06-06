<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class GeoCache extends Eloquent {
	protected $table = 'geo_cache';
	
	protected $fillable = array('geo_id', 'name');
}

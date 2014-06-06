<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class GeoAlternatenames extends Eloquent {
	protected $table = 'geo_alternatenames';
	
	protected $fillable = array(
		'value'
	);
	
	public function geo() {
		return $this->belongsTo('Geo');
	}
}

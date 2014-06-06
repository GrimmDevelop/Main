<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Geo extends Eloquent {
	protected $table = 'geo';
	
	protected $fillable = array(
		'id',
		'name',
		'asciiname',
		'alternatenames',
		'latitude',
		'longitude',
		'feature_class',
		'feature_code',
		'country_code',
		'cc2',
		'admin1_code',
		'admin2_code',
		'admin3_code',
		'admin4_code',
		'population',
		'elevation',
		'dem',
		'timezone',
		'modification_date'
	);
	
	protected $hidden = array('population', 'elevation', 'dem', 'admin1_code', 'admin2_code', 'admin3_code', 'admin4_code');
	
	public function alternatenames() {
		return $this->hasMany('GeoAlternatenames');
	}
}

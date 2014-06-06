<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class GeoSend extends Eloquent {
	protected $table = 'geo_letter_send';
	
	protected $fillable = array('geo_id', 'letter_id', 'maybe');
}

<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class GeoRecv extends Eloquent {
	protected $table = 'geo_letter_recv';
	
	protected $fillable = array('geo_id', 'letter_id', 'maybe');
}

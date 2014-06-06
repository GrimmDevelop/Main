<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Queue extends Eloquent {
	protected $table = 'queue';
	
	protected $fillable = array('action', 'data', 'pending');
}

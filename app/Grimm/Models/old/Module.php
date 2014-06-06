<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Module extends Eloquent {
	protected $table = 'modules';
	
	protected $fillable = array('name', 'is_private', 'is_hidden');
	
	public function users() {
		return $this->belongsToMany('User')->withTimestamps();
	}
}

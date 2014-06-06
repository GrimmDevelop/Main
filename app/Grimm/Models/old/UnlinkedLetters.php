<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UnlinkedLetters extends Eloquent {
	protected $table = 'unlinked_letters';
	
	protected $fillable = array('letter_id', 'type', 'multiple');
	
	public function letter() {
		return $this->belongsTo('Brief');
	}
}

<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ShareAccess extends Eloquent {
	protected $table = 'shareaccess';
	
	protected $fillable = array('user_id', 'tokenA', 'tokenB');
	
	public function user() {
		return $this->belongsTo('User');
	}
	
	public static function findByUser(User $user) {
		return self::where('user_id', $user->id)->first();
	}
	
	public static function findByToken($tokenA, $tokenB) {
		return self::where('tokenA', $tokenA)->where('tokenB', $tokenB)->first();
	}
}

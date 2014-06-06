<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent {
	protected $table = 'users';
	
	protected $fillable = array('username', 'password', 'email');
	
	public function modules($hidden = true) {
		$modules = [];
		
		if($this->is_admin) {
			if(!$hidden) {
				return Module::where('is_hidden', false)->orderBy('name')->get()->toArray();
			}
			return Module::orderBy('name')->get()->toArray();
		}
		
		$builder = Module::where('is_private', false);
		
		if(!$hidden) {
			$builder->where('is_hidden', false);
		}
		
		foreach($builder->get() as $module) {
			$modules[] = $module;
		}
		
		$builder = $this->belongsToMany('Module')->where('is_private', 1);
		
		if(!$hidden) {
			$builder->where('is_hidden', false);
		}
		
		foreach($builder->get() as $module) {
			$modules[] = $module;
		}
		
		usort($modules, function($a, $b) {
			return strcmp($a->name, $b->name);
		});
		
		return $modules;
	}
	
	public function hasAccess(Module $module) {
		if($this->is_admin) {
			return true;
		}
		
		if(!$module->is_private) {
			return true;
		}
		
		foreach($this->modules() as $item) {
			if($item->name == $module->name) {
				return true;
			}
		}
		
		return false;
	}
	
}

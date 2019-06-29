<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Templates extends Model {
	use SoftDeletes;

	protected $fillable = [
		'name', 
		'created_by',
		'updated_by',
		'deleted_by',
		'deleted_at'
	];

	protected $hidden = [
		'deleted_at'
	];

	public function checklists(){
		return $this->hasMany('App\Checklists', 'template_id', 'id');
	}
}

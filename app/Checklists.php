<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checklists extends Model {
	use SoftDeletes;

	protected $fillable = [
		'template_id',
		'name', 
		'description', 
		'due_interval', 
		'due_unit', 
		'due', 
		'urgency',
		'is_completed', 
		'created_by',
		'updated_by',
		'deleted_by',
		'deleted_at'
	];

	protected $hidden = [
		'created_by',
		'updated_by',
		'deleted_by',
		'deleted_at',
		'created_at',
		'updated_at'
	];

	public function template(){
		return $this->belongsTo('App\Templates', 'template_id', 'id');
	}

	public function items(){
		return $this->hasMany('App\Items', 'checklist_id', 'id');
	}
}

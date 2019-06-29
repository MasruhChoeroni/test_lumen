<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Items extends Model {
	use SoftDeletes;

	protected $fillable = [
		'checklist_id', 
		'name', 
		'due_interval', 
		'due_unit', 
		'due', 
		'urgency',
		'is_completed', 
		'completed_by',
		'completed_at',
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

	public function checklist(){
		return $this->belongsTo('App\Checklists', 'checklist_id', 'id');
	}
}

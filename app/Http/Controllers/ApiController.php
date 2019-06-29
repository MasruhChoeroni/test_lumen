<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Templates;
use App\Checklists;
use App\Items;

class ApiController extends Controller {

	// Template
	public function template_index(){
		$data 	= Templates::with('checklists')->with('checklists.items')->get();

		return response()->json([
			'status'	=> true,
			'message'	=> 'load data success.',
			'data'		=> $data
		]);
	}

	public function template_create(Request $request){
		DB::beginTransaction();
		try {
			$create 	= Templates::create([
				'name'		=> $request->input('name')
			]);

			// Checklists
			$checklists = $request->input('checklists');

			if (count($checklists)>0) {

				for ($i=0; $i < count($checklists); $i++) { 
					$create_checklist 	= Checklists::create([
						'template_id'		=> $create->id,
						'name' 				=> $checklists[$i]['name'],
						'description'		=> $checklists[$i]['description'],
						'due_interval'		=> $checklists[$i]['due_interval'],
						'due_unit'			=> $checklists[$i]['due_unit'],
						'urgency'			=> $checklists[$i]['urgency'],
						'created_by'		=> Auth::user()->id,
						'updated_by'		=> Auth::user()->id
					]);
					// Items
					$items 	= $checklists[$i]['items'];

					if (count($items)>0) {
						for ($j=0; $j < count($items); $j++) { 
							$create_items 	= Items::create([
								'checklist_id'		=> $create_checklist->id,
								'name' 				=> $items[$j]['name'],
								'due_interval'		=> $items[$j]['due_interval'],
								'due_unit'			=> $items[$j]['due_unit'],
								'urgency'			=> $items[$j]['urgency'],
								'created_by'		=> Auth::user()->id,
								'updated_by'		=> Auth::user()->id
							]);
						}
					}
				}
			}

			DB::commit();
			return response()->json([
				'status'		=> true,
				'message'		=> 'Create success.',
				'note'			=> 'ID : ' . $create->id,
				'data'			=> Templates::where('id', $create->id)
									->with('checklists')->with('checklists.items')->get()
			], 201);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status'		=> false,
				'message'		=> 'Create failed.',
				'note'			=> $e->getMessage()
			], 400);
		}
	}

	public function template_update(Request $request){
		DB::beginTransaction();
		try {
			$id 		= $request->input('id');
			$name 		= $request->input('name');
			$update 	= Templates::where('id', $id)
							->update([
								'name'		=> $name
							]);
			DB::commit();
			return response()->json([
				'status'		=> true,
				'message'		=> 'Update success.',
				'note'			=> $update,
			], 201);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status'		=> false,
				'message'		=> 'Update failed.',
				'note'			=> $e->getMessage()
			], 400);
		}
	}

	public function template_delete(Request $request){
		DB::beginTransaction();
		try {
			$id 		= $request->input('id');
			$delete 	= Templates::where('id', $id)->delete();
			DB::commit();
			return response()->json([
				'status'		=> true,
				'message'		=> 'Delete success.',
				'note'			=> $delete,
			], 201);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status'		=> false,
				'message'		=> 'Delete failed.',
				'note'			=> $e->getMessage()
			], 400);
		}
	}

	// Checklist
	public function checklist_index(){
		$data 	= Checklists::with('template')->with('items')->get();

		return response()->json([
			'status'	=> true,
			'message'	=> 'load data success.',
			'data'		=> $data
		]);
	}

	public function checklist_create(Request $request){
		DB::beginTransaction();
		try {
			$create_checklist 	= Checklists::create([
				'template_id'		=> $request->input('template_id'),
				'name' 				=> $request->input('name'),
				'description'		=> $request->input('description'),
				'due_interval'		=> $request->input('due_interval'),
				'due_unit'			=> $request->input('due_unit'),
				'urgency'			=> $request->input('urgency'),
				'created_by'		=> Auth::user()->id,
				'updated_by'		=> Auth::user()->id
			]);
					
			// Items
			$items 	= $request->input('items');

			if (count($items)>0) {
				for ($j=0; $j < count($items); $j++) { 
					$create_items 	= Items::create([
						'checklist_id'		=> $create_checklist->id,
						'name' 				=> $items[$j]['name'],
						'due_interval'		=> $items[$j]['due_interval'],
						'due_unit'			=> $items[$j]['due_unit'],
						'urgency'			=> $items[$j]['urgency'],
						'created_by'		=> Auth::user()->id,
						'updated_by'		=> Auth::user()->id
					]);
				}
			}

			DB::commit();
			return response()->json([
				'status'		=> true,
				'message'		=> 'Create success.',
				'note'			=> 'ID : ' . $create_checklist->id,
				'data'			=> Checklists::where('id', $create_checklist->id)
									->with('template')->with('items')->get()
			], 201);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status'		=> false,
				'message'		=> 'Create failed.',
				'note'			=> $e->getMessage()
			], 400);
		}
	}

	public function checklist_update(Request $request){
		DB::beginTransaction();
		try {
			$update 	= Checklists::where('id', $request->input('id'))
							->update([
								'template_id'		=> $request->input('template_id'),
								'name' 				=> $request->input('name'),
								'description'		=> $request->input('description'),
								'due_interval'		=> $request->input('due_interval'),
								'due_unit'			=> $request->input('due_unit'),
								'urgency'			=> $request->input('urgency'),
								'updated_by'		=> Auth::user()->id
							]);
			DB::commit();
			return response()->json([
				'status'		=> true,
				'message'		=> 'Update success.',
				'note'			=> $update,
			], 201);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status'		=> false,
				'message'		=> 'Update failed.',
				'note'			=> $e->getMessage()
			], 400);
		}
	}

	public function checklist_delete(Request $request){
		DB::beginTransaction();
		try {
			$delete 	= Checklists::where('id', $request->input('id'))->delete();
			DB::commit();
			return response()->json([
				'status'		=> true,
				'message'		=> 'Delete success.',
				'note'			=> $delete,
			], 201);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status'		=> false,
				'message'		=> 'Delete failed.',
				'note'			=> $e->getMessage()
			], 400);
		}
	}

	// Item
	public function item_index(){
		$data 	= Items::with('checklist')->with('checklist.template')->get();

		return response()->json([
			'status'	=> true,
			'message'	=> 'load data success.',
			'data'		=> $data
		]);
	}

	public function item_create(Request $request){
		DB::beginTransaction();
		try {
			$create_items 	= Items::create([
				'checklist_id'		=> $request->input('checklist_id'),
				'name' 				=> $request->input('name'),
				'due_interval'		=> $request->input('due_interval'),
				'due_unit'			=> $request->input('due_unit'),
				'urgency'			=> $request->input('urgency'),
				'created_by'		=> Auth::user()->id,
				'updated_by'		=> Auth::user()->id
			]);

			DB::commit();
			return response()->json([
				'status'		=> true,
				'message'		=> 'Create success.',
				'note'			=> 'ID : ' . $create_items->id,
				'data'			=> Items::where('id', $create_items->id)
									->with('checklist')->with('checklist.template')->get()
			], 201);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status'		=> false,
				'message'		=> 'Create failed.',
				'note'			=> $e->getMessage()
			], 400);
		}
	}

	public function item_update(Request $request){
		DB::beginTransaction();
		try {
			$update 	= Items::where('id', $request->input('id'))
							->update([
								'checklist_id'		=> $request->input('checklist_id'),
								'name' 				=> $request->input('name'),
								'due_interval'		=> $request->input('due_interval'),
								'due_unit'			=> $request->input('due_unit'),
								'urgency'			=> $request->input('urgency'),
								'updated_by'		=> Auth::user()->id
							]);
			DB::commit();
			return response()->json([
				'status'		=> true,
				'message'		=> 'Update success.',
				'note'			=> $update,
			], 201);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status'		=> false,
				'message'		=> 'Update failed.',
				'note'			=> $e->getMessage()
			], 400);
		}
	}

	public function item_delete(Request $request){
		DB::beginTransaction();
		try {
			$delete 	= Items::where('id', $request->input('id'))->delete();
			DB::commit();
			return response()->json([
				'status'		=> true,
				'message'		=> 'Delete success.',
				'note'			=> $delete,
			], 201);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status'		=> false,
				'message'		=> 'Delete failed.',
				'note'			=> $e->getMessage()
			], 400);
		}
	}

	public function item_completed(Request $request){
		DB::beginTransaction();
		try {
			$date_full 		= date('Y-m-d H:i:s');
			$id 			= $request->input('data');
			if (count($id)>0) {
				for ($i=0; $i < count($id); $i++) { 
					$update 	= Items::where('id', $id[$i]['item_id'])
								->update([
									'is_completed'		=> true,
									'completed_by' 		=> Auth::user()->id,
									'completed_at'		=> $date_full,
									'updated_by'		=> Auth::user()->id
								]);
					$checklist 	= Checklists::where('id', $id[$i]['checklist_id'])
									->whereHas('items', function($query1){
										$query1->where('is_completed', false);
									})->get();
					if ($checklist->count()==0) {
						$update_checklist 	= Checklists::where('id', $id[$i]['checklist_id'])
												->update([
													'is_completed'		=> true,
													'updated_by'		=> Auth::user()->id
												]);
					}
				}
			}
					
			DB::commit();
			return response()->json([
				'status'		=> true,
				'message'		=> 'Update success.',
				'note'			=> '',
			], 201);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status'		=> false,
				'message'		=> 'Update failed.',
				'note'			=> $e->getMessage()
			], 400);
		}
	}

	public function item_incompleted(Request $request){
		DB::beginTransaction();
		try {
			$date_full 		= date('Y-m-d H:i:s');
			$id 			= $request->input('data');
			if (count($id)>0) {
				for ($i=0; $i < count($id); $i++) { 
					$update 	= Items::where('id', $id[$i]['item_id'])
								->update([
									'is_completed'		=> false,
									'completed_by' 		=> null,
									'completed_at'		=> null,
									'updated_by'		=> Auth::user()->id
								]);
					$checklist 	= Checklists::where('id', $id[$i]['checklist_id'])
									->whereHas('items', function($query1){
										$query1->where('is_completed', false);
									})->get();
					if ($checklist->count()>0) {
						$update_checklist 	= Checklists::where('id', $id[$i]['checklist_id'])
												->update([
													'is_completed'		=> false,
													'updated_by'		=> Auth::user()->id
												]);
					}
				}
			}
					
			DB::commit();
			return response()->json([
				'status'		=> true,
				'message'		=> 'Update success.',
				'note'			=> '',
			], 201);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'status'		=> false,
				'message'		=> 'Update failed.',
				'note'			=> $e->getMessage()
			], 400);
		}
	}
}

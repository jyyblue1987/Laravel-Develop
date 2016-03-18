<?php

class UnitController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// delete action
		$ids = Input::get('ids');
		if( !empty($ids) )
		{
			Unit::whereIn('id', $ids)->get()->each(function($unit) {
				$unit->delete();
			});	
			return Redirect::back()->withInput();				
		}

		$query = Unit::sortable();
		
		$search = Input::get('search');
		if( !empty($search) )
		{
			$query->where(function($searchquery)
				{
					$search = '%' . Input::get('search') . '%';
					$searchquery->where('info', 'like', $search);
				});	
		}
		
		$project_id = Input::get('project_id');
		if( !empty($project_id) && $project_id != 0 )
		{
			$query->where(function($searchquery)
				{
					$project_id = Input::get('project_id');
					$searchquery->where('project_id', '=', $project_id);
				});	
		}
		
		$reservestate_id = Input::get('reservestate_id');
		if( !empty($reservestate_id) && $reservestate_id != 0 )
		{
			$query->where(function($searchquery)
				{
					$reservestate_id = Input::get('reservestate_id');
					$searchquery->where('reservestate_id', '=', $reservestate_id);
				});	
		}
		

		$pagesize = Input::get('pagesize');
		if( empty($pagesize) )
			$pagesize = 10;		
		
		$unit = $query->paginate($pagesize);	
		
		Input::flashOnly('search', 'project_id', 'reservestate_id');
				
		$projects = ['0' => '-- Select Project --'] + User::projectlist();
		$state = ['0' => '-- Select State --'] + ReserveState::lists('name', 'id');
		
		return View::make('unit.list')->with('unit', $unit)
											->with('state', $state)
											->with('projects', $projects)
											->with('pagesize',$pagesize);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$unit = new Unit();
		$unit->floorplan = "default_thumbnail.jpg";
		
		$projects = User::projectlist();
		$state = ReserveState::lists('name', 'id');
		
		return View::make('unit.form')->with('unit', $unit)
								->with('state', $state)
								->with('projects', $projects);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$unit = Unit::create(Input::all());
		
		if( empty($unit) )
		{
			$message = 'Internal Server error';
			return Redirect::back()
					->withErrors('message', $message)
					->withInput();
		}	
		
		Unit::onCreateUnit($unit);
		
		$message = 'SUCCESS';	
		
		return Redirect::back()
						->withErrors([$message])
						->withInput();
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$unit = Unit::find($id);				
		return View::make('unit.view')->with('unit', $unit);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$unit = Unit::find($id);	
		
		$projects = User::projectlist();		
		$state = ReserveState::lists('name', 'id');
		
		return View::make('unit.form')->with('unit', $unit)
										->with('state', $state)
										->with('projects', $projects);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$unit = Unit::find($id);
		
		$prevstate = $unit->reservestate_id;
		
		if (!$unit->update(Input::all())) {
			return Redirect::back()
					->withErrors('message', $message)
					->withInput();
		}	
		
		Unit::onUpdateUnit($unit, $prevstate);
		
		$message = 'SUCCESS';
		
		
		return Redirect::back()
						->withErrors([$message])
						->withInput();		
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$unit = Unit::find($id);
		$unit->delete();

		return Redirect::back();
		
	}


}

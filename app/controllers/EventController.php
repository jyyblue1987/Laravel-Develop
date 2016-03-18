<?php

class EventController extends BaseController {

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
			DB::table('wc_event')->whereIn('id', $ids)->delete();			
			return Redirect::back()->withInput();				
		}

		$query = PropertyEvent::sortable();
		
		$search = Input::get('search');
		if( !empty($search) )
		{
			$query->where(function($searchquery)
				{
					$search = '%' . Input::get('search') . '%';
					$searchquery->where('title', 'like', $search)
					 ->orWhere('desc', 'like', $search);
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
	
		$member_id = Input::get('member_id');
		if( !empty($member_id) && $member_id != 0 )
		{
			$query->where(function($searchquery)
				{
					$member_id = Input::get('member_id');
					$searchquery->where('member_id', '=', $member_id);
				});	
		}
		
		$pagesize = Input::get('pagesize');
		if( empty($pagesize) )
			$pagesize = 10;		
		
		$event = $query->paginate($pagesize);	
		
		Input::flashOnly('search', 'project_id', 'member_id');
				
		$projects = ['0' => '-- Select Project --'] + User::projectlist();
		$member = ['0' => '-- Select Member --'] + Member::lists('fullname', 'id');
		
		return View::make('event.list')->with('event', $event)
											->with('member', $member)
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
		$event = new Unit();
		$event->floorplan = "default_thumbnail.jpg";
		
		$projects = User::projectlist();
		$member = Member::lists('fullname', 'id');		
		
		return View::make('event.form')->with('event', $event)
								->with('member', $member)
								->with('projects', $projects);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$event = Unit::create(Input::all());
		if( empty($event) )
		{
			$message = 'Internal Server error';
			return Redirect::back()
					->withErrors('message', $message)
					->withInput();
		}	
		
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
		$event = Unit::find($id);				
		return View::make('event.view')->with('event', $event);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$event = Unit::find($id);	
		
		$projects = User::projectlist();		
		$state = ReserveState::lists('name', 'id');
		
		return View::make('event.form')->with('event', $event)
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
		$event = Unit::find($id);
			
		if (!$event->update(Input::all())) {
			return Redirect::back()
					->withErrors('message', $message)
					->withInput();
		}	
		
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
		$event = Unit::find($id);
		$event->delete();

		return Redirect::back();
		
	}


}

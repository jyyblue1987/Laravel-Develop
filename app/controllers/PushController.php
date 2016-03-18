<?php

class PushController extends BaseController {

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
			DB::table('wc_push')->whereIn('id', $ids)->delete();			
			return Redirect::back()->withInput();				
		}

		// $admin = Auth::user();
		$admin = $_SESSION["admin"];
		$user_id = $admin->id;
		
		$query = Push::sortable()->where('user_id', '=', $user_id);
		
		$search = Input::get('search');
		if( !empty($search) )
		{
			$query->where(function($searchquery)
				{
					$search = '%' . Input::get('search') . '%';
					$searchquery->where('message', 'like', $search);
				});	
		}
		
		$project_id = Input::get('project_id');
		if( !empty($project_id) )
		{
			$query->where(function($searchquery)
				{
					$project_id = '%|' . Input::get('project_id') . '|%';				
					$searchquery->where('class', 'like', $project_id);
				});	
		}
					
		$pagesize = Input::get('pagesize');
		if( empty($pagesize) )
			$pagesize = 10;		
		
		$projects = ['0' => '-- Select Project --'] + User::projectlist();
		
		$push = $query->paginate($pagesize);	
		
		Input::flashOnly('search', 'project_id');
				
		return View::make('push.list')->with('push', $push)				
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
		$push = new Push();

		$admin = $_SESSION["admin"];
		
		$push->sendstate_id = 1;
		$push->user_id = $admin->id;
		$push->pushgroup_id = 1;
		
		// $admin = Auth::user();
		$admin = $_SESSION["admin"];
		$user = User::find($admin->id);
		$projects = $user->projects()->get();
		
		$group = PushGroup::lists('name', 'id');
			
		return View::make('push.form')->with('push', $push)
											->with('projects', $projects)	
											->with('project_selected', array())											
											->with('group',$group);
	
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$push = Push::create(Input::all());
		if( empty($push) )
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
		$push = Push::find($id);				
		return View::make('push.view')->with('push', $push);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$push = Push::find($id);	
		
		$admin = $_SESSION["admin"];
		$user = User::find($admin->id);
		

		$project_ids = explode("|", $push->class);
		
		$project_unselected = $user->projects()->whereNotIn('id', $project_ids)->get();
		$project_selected = $user->projects()->whereIn('id', $project_ids)->get();
				
		$group = PushGroup::lists('name', 'id');
		
		return View::make('push.form')->with('push', $push)
											->with('projects', $project_unselected)		
											->with('project_selected', $project_selected)
											->with('group',$group);				
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$push = Push::find($id);
			
		if (!$push->update(Input::all())) {
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
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$push = Push::find($id);
		$push->delete();

		return Redirect::back();
		
	}
	
	public function send($id)
	{
		$push = Push::find($id);
				
		$pushgroup = $push->pushgroup_id;
		
		$admin = $_SESSION["admin"];
		$user = User::find($admin->id);
		
		if( $pushgroup == 1 ) // project
		{
			$project_ids = explode("|", $push->class);
			
			$projects = $user->projects()->whereIn('id', $project_ids)->get();		
			
		}
		else
		{
			
		}
		
		$message = 'SUCCESS';
		
		return Redirect::back()
				->withErrors([$message])
				->withInput();	
	}


}

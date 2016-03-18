<?php

class ProjectController extends BaseController {

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
			DB::table('wc_project')->whereIn('id', $ids)->delete();			
			return Redirect::back()->withInput();				
		}
/*
		for($i = 0; $i < 100; $i++)
		{	
			$project = new Project();
			$project->name = 'project name ' . $i;
			$project->desc = 'Desc '  . $i;			
			$project->save();
		}	
*/
		$admin = $_SESSION["admin"];
		
		$query = Project::sortable()->where('user_id', '=', $admin->id);
		
		$search = Input::get('search');
		if( !empty($search) )
		{
			$query->where(function($searchquery)
				{
					$search = '%' . Input::get('search') . '%';
					$searchquery->where('name', 'like', $search)						
							->orWhere('desc', 'like', $search);
				});	
		}
		
		$pagesize = Input::get('pagesize');
		if( empty($pagesize) )
			$pagesize = 10;		
		
		$project = $query->paginate($pagesize);	
		
		Input::flashOnly('search');
		
		return View::make('project.list')->with('project', $project)											
											->with('pagesize',$pagesize);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$project = new Project();
		$project->thumbnail = "default_thumbnail.jpg";
		$project->floorplan = "default_floorplan.jpg";
		
		return View::make('project.form')->with('project', $project);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$project = Project::create(Input::all());
		if( empty($project) )
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
		$project = Project::find($id);				
		return View::make('project.view')->with('project', $project);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$project = Project::find($id);					
		return View::make('project.form')->with('project', $project);										
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$project = Project::find($id);
			
		if (!$project->update(Input::all())) {
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
		$project = Project::find($id);
		$project->delete();

		return Redirect::back();
		
	}


}

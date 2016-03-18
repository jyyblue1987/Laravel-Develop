<?php

class PropertyController extends BaseController {

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
			DB::table('wc_property')->whereIn('id', $ids)->delete();			
			return Redirect::back()->withInput();				
		}

		$query = Property::sortable();
		
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
					
		$pagesize = Input::get('pagesize');
		if( empty($pagesize) )
			$pagesize = 10;		
		
		$property = $query->paginate($pagesize);	
		
		Input::flashOnly('search');
				
		return View::make('property.list')->with('property', $property)											
											->with('pagesize',$pagesize);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$property = new Property();
		
		$property->thumbnail = "default_thumbnail.jpg";
		$property->location_url = "default_thumbnail.jpg";
		$property->state_id = 1;

	
		return View::make('property.form')->with('property', $property);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$property = Property::create(Input::all());
		if( empty($property) )
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
		$property = Property::find($id);				
		return View::make('property.view')->with('property', $property);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$property = Property::find($id);	
		
		$projects = User::projectlist();		
		$state = ReserveState::lists('name', 'id');
		
		return View::make('property.form')->with('property', $property)
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
		$property = Property::find($id);
			
		if (!$property->update(Input::all())) {
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
		$property = Property::find($id);
		$property->delete();

		return Redirect::back();
		
	}
}

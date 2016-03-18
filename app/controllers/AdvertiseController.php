<?php

class AdvertiseController extends BaseController {

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
			DB::table('wc_advertise')->whereIn('id', $ids)->delete();			
			return Redirect::back()->withInput();				
		}

		$query = Advertise::sortable();
		
		$search = Input::get('search');
		if( !empty($search) )
		{
			$query->where(function($searchquery)
				{
					$search = '%' . Input::get('search') . '%';
					$searchquery->where('title', 'like', $search)
					 ->orWhere('link', 'like', $search);
				});	
		}
					
		$pagesize = Input::get('pagesize');
		if( empty($pagesize) )
			$pagesize = 10;		
		
		$advertise = $query->paginate($pagesize);	
		
		Input::flashOnly('search');
				
		return View::make('advertise.list')->with('advertise', $advertise)											
											->with('pagesize',$pagesize);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$advertise = new Advertise();
		
		$advertise->startdate = date('Y-m-d', time());
		$advertise->enddate = date('Y-m-d', time());
		$advertise->thumbnail = "default_thumbnail.jpg";
	
		return View::make('advertise.form')->with('advertise', $advertise);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$advertise = Advertise::create(Input::all());
		if( empty($advertise) )
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
		$advertise = Advertise::find($id);				
		return View::make('advertise.view')->with('advertise', $advertise);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$advertise = Advertise::find($id);	
		
		$projects = User::projectlist();		
		$state = ReserveState::lists('name', 'id');
		
		return View::make('advertise.form')->with('advertise', $advertise)
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
		$advertise = Advertise::find($id);
			
		if (!$advertise->update(Input::all())) {
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
		$advertise = Advertise::find($id);
		$advertise->delete();

		return Redirect::back();
		
	}


}

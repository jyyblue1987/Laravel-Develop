<?php

class AgentController extends BaseController {

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
			DB::table('wc_agent')->whereIn('id', $ids)->delete();			
			return Redirect::back()->withInput();				
		}
/*
		for($i = 0; $i < 100; $i++)
		{	
			$user = new Agent();
			$user->username = 'agent' . $i;
			$user->fullname = 'Agnet '  . $i;
			$user->email = 'agent@gmail.com' . $i;
			$user->contact = 'contactno_' . $i;
			$user->password = Hash::make('123456');			
			$user->save();
		}	
*/	
		// $admin = Auth::user();
		$admin = $_SESSION["admin"];
		
		$query = Agent::sortable();
		
		$search = Input::get('search');
		if( !empty($search) )
		{
			$query->where(function($searchquery)
				{
					$search = '%' . Input::get('search') . '%';
					$searchquery->where('username', 'like', $search)						
							->orWhere('fullname', 'like', $search)	
							->orWhere('email', 'like', $search);
				});	
		}
		else 
			$search = "";

		$pagesize = Input::get('pagesize');
		if( empty($pagesize) )
			$pagesize = 10;		
		
		$users = $query->paginate($pagesize);	
		
		Input::flashOnly('search');
		
		return View::make('agent.list')->with('users', $users)
											->with('pagesize',$pagesize);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$user = new Agent();		
		return View::make('agent.form')->with('user', $user);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if( Input::get('password') !== Input::get('password2') )
			$message = Functions::GetMessage ('ACCOUNT_PASS_MISMATCH');
		else if( strlen(Input::get('password')) < 6 || strlen(Input::get('password')) > 32 )
			$message = Functions::GetMessage('ACCOUNT_PASS_CHAR_LIMIT', array(6, 32));
		else
		{
			$user = Agent::create(Input::all());
			if( empty($user) )
			{
				return Redirect::back()
						->withErrors('message', $message)
						->withInput();
			}	
			
			$user->username = $user->fullname;
			$user->role = 2;
			$user->password = Hash::make(Input::get('password'));
			$user->save();			
			
			$message = 'SUCCESS';
		}			
		
		$user = new Agent();
		
		$user->fullname = Input::get('fullname');
		$user->email = Input::get('email');
		$user->contact = Input::get('contact');

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
		$user = Agent::find($id);				
		$state = State::lists('name', 'id');
		return View::make('agent.view')->with('user', $user)
										->with('state', $state);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = Agent::find($id);				
		return View::make('agent.form')->with('user', $user);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = Agent::find($id);
		
		if (!$user->update(Input::all())) {
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
		$user = Agent::find($id);
		$user->delete();

		return Redirect::back();
		
	}


}

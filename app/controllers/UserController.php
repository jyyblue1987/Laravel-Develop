<?php

require_once('openfire_api.php');

function convertMobileNumber($number)
{
	return "+" . str_replace("_", " ", $number);
}		
class UserController extends Controller
{
	function login()
	{
		return View::make('login'); //
	}
	
	function logout()
	{
		Auth::logout();		
		unset($_SESSION["admin"]);
		
		return Redirect::to('login');
	}
	
	public function postLogin()
	{
		/*
		$default_email = 'admin@gmail.com';
		if (User::where('email', '=', $default_email)->exists() === false) {
			$user = new User();
			$user->username = 'admin';
			$user->fullname = 'Administrator';
			$user->email = $default_email;
			$user->password = Hash::make('ijmadmin');
			$user->save();
		} */
		
		$email = Input::get('email');
		$password = Input::get('password');
		
		if (Auth::attempt(['email' => $email, 'password' => $password])) {
			$admin = Auth::user();
			
			$_SESSION["admin"] = $admin;
			
			if( $admin->role === 1 )	// super admin(site admin)
				return Redirect::to('/');
			if( $admin->role === 2 )    // Propery Owners
				return Redirect::to('agent');
			$this->logout();
        }
		else
		{
			return Redirect::to('login');
		}	
	}
	
	function index()
	{
		$admin = $_SESSION["admin"];
		// $admin = Auth::user();
		if( $admin->role !== 1 )	// super admin(site admin)
		{
			return Redirect::to('agent');
		}
		
		// delete action
		$ids = Input::get('ids');

		if( !empty($ids) )
		{
			DB::table('wc_user')->whereIn('id', $ids)->delete();			
			return Redirect::back()->withInput();				
		}
/*
		for($i = 0; $i < 1000; $i++)
		{	
			$user = new User();
			$user->username = 'property' . $i;
			$user->fullname = 'Property Owner'  . $i;
			$user->email = 'admin@gmail.com' . $i;
			$user->password = Hash::make('123456');
			$user->role = 2;
			$user->save();
		}	
*/		
	
		if( $admin->role !== 1 )	// super admin(site admin)
		{
			return Redirect::to('agent/index');
		}
		
		$query = User::where('role', '=', '2')->sortable();
		
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
		
		return View::make('users.list')->with('users', $users)
											->with('pagesize',$pagesize);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$user = new User();		
		
		$user->fullname = Input::old('fullname', '');
		$user->email = Input::old('email', '');
		$user->contact = Input::old('contact', '');
		
		return View::make('users.form')->with('user', $user);
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
			$username = Input::get('fullname', '');
			
			$user = User::where('username', '=', $username)->first();
			
			if( empty($user) == false )
			{
				$message = 'User exists';				
				return Redirect::back()
						->withErrors([$message])
						->withInput();
			}
								
			$user = User::create(Input::all());
			if( empty($user) )
			{				
				$message = 'Internal Server error';
				return Redirect::back()
						->withErrors('message', $message)
						->withInput();
			}	
			
			$openfire_id = 'ijmadmin_' . $username;			
			$openfire_user = getUser($openfire_id);
		
			if( $openfire_user['code'] != 200 ) // not exist openfire server
				addUser($openfire_id, strrev($openfire_id));
			
			$user->username = $user->fullname;
			$user->role = 2;
			$user->password = Hash::make(Input::get('password'));
			$user->save();			
			
			$message = 'SUCCESS';
		}			

		
		return Redirect::back()
						->withErrors([$message]);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::find($id);				
		return View::make('users.form')->with('user', $user);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if( Input::get('password') !== Input::get('password2') )
			$message = Functions::GetMessage ('ACCOUNT_PASS_MISMATCH');
		else if( strlen(Input::get('password')) < 6 || strlen(Input::get('password')) > 32 )
			$message = Functions::GetMessage('ACCOUNT_PASS_CHAR_LIMIT', array(6, 32));
		else
		{
			$user = User::find($id);
			
			if (!$user->update(Input::all())) {
				return Redirect::back()
						->withErrors('message', $message)
						->withInput();
			}	
			
			$user->username = $user->fullname;
			$user->password = Hash::make(Input::get('password'));
			$user->save();
			
			$message = 'SUCCESS';
		}
		
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
		$user = User::find($id);
		$user->delete();

		return Redirect::back();
		
	}

}

<?php 

class User extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_user';
	
	protected $fillable = array('username', 'email', 'fullname', 'contact', 'role', 'state', 'device', 'pushkey', 'token');
	protected $hidden = array('password');
	
	public function state()
    {
		return $this->belongsTo('State');
        //return $this->hasOne('Phone', 'foreign_key', 'local_key');
    }
	
	public function projects()
    {
        return $this->hasMany('Project');
    }
	
	public function push()
    {
        return $this->hasMany('Push');
    }
	
	public function agent()
    {
        return $this->hasManyThrough('Agent', 'Project');
    }


	public static function projectlist()
	{
		$admin = $_SESSION["admin"];
		// $admin = Auth::user();
		$user = User::find($admin->id);
		$projects = $user->projects()->lists('name', 'id');
		
		return $projects;
	}

	public static function checkValidity()
	{
		$id = Input::get('id');
		$token = Input::get('token');

		if( empty($id) || empty($token) ) 
			return null;
		
		$user = User::find($id);
		
		if( empty($user) )	// user does not exist
			return null;
		
		if( $user->token != $token )
			return null;		

		return $user;
	}

}
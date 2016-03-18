<?php 

class Agent extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_agent';
	
	protected $fillable = array('username', 'email', 'fullname', 'contact', 'passport', 'address', 'postcode', 'city', 'state_id', 'device', 'pushkey', 'token');
	
	protected $hidden = array('password');

	public function country()
    {
		return $this->belongsTo('Country');
        //return $this->hasOne('Phone', 'foreign_key', 'local_key');
    }
	
	public function state()
    {
		return $this->belongsTo('State');
        //return $this->hasOne('Phone', 'foreign_key', 'local_key');
    }
	
	public function projects()
    {
		return $this->belongsToMany('Project', 'wc_subscribe');		        
    }
	
	
	public static function checkValidity()
	{
		$id = Input::get('id');
		$token = Input::get('token');

		if( empty($id) || empty($token) ) 
			return null;
		
		$user = Agent::find($id);
		
		if( empty($user) )	// user does not exist
			return null;
		
		if( $user->token != $token )
			return null;		

		return $user;
	}

}
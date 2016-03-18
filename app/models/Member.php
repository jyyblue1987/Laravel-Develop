<?php 

class Member extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_member';
	
	protected $fillable = array('username', 'email', 'fullname', 'contact', 'point', 'state_id', 'device', 'pushkey');
	protected $hidden = array('password');

	public function state()
    {
		return $this->belongsTo('State');        
    }
	public function user()
    {
		return $this->belongsTo('User');        
    }
	
	public function events()
    {
        return $this->hasMany('MemberEvent');
    }
	
	public static function checkValidity()
	{
		$id = Input::get('id');
		$token = Input::get('token');

		if( empty($id) || empty($token) ) 
			return null;
		
		$user = Member::find($id);
		
		if( empty($user) )	// user does not exist
			return null;
		
		if( $user->token != $token )
			return null;		

		return $user;
	}
}
<?php 

class Project extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_project';
	
	protected $fillable = array('name', 'desc', 'thumbnail', 'floorplan', 'user_id', 'link');	
	
	public function user()
    {
		return $this->belongsTo('User');
        //return $this->hasOne('Phone', 'foreign_key', 'local_key');
    }
	
	public function news()
    {
        return $this->hasMany('News');
    }
	
	public function agent()
    {
		return $this->belongsToMany('Agent', 'wc_subscribe');		        
    }
	
	public function units()
    {
        return $this->hasMany('Unit');
    }
}
<?php 

class Push extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_push';
	
	protected $fillable = array('message', 'pushgroup_id', 'class', 'sendstate_id', 'user_id');
	
	public function sendstate()
    {
		return $this->belongsTo('SendState');
        //return $this->hasOne('Phone', 'foreign_key', 'local_key');
    }
	
	public function group()
    {
		return $this->belongsTo('PushGroup');
        //return $this->hasOne('Phone', 'foreign_key', 'local_key');
    }

	public function user()
    {
		return $this->belongsTo('User');
        //return $this->hasOne('Phone', 'foreign_key', 'local_key');
    }


}
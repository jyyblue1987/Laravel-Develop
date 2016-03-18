<?php 

class OfflineMessage extends Eloquent { 
	protected $table = 'ofoffline';
	
	public $timestamps = false;
	
	public function user()
    {
        return $this->belongsTo('App\Models\User', 'username', 'username');
    }
}
<?php 

class PushTask extends Eloquent { 
	protected $table = 'hc_schedule';
	
	public $timestamps = false;
	
	public function user()
    {
        return $this->belongsTo('App\Models\OfflineMessage', 'lastid', 'messageID');
    }
}
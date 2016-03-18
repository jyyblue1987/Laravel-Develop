<?php 

class Reserve extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_reserve';
	
	protected $fillable = array('agent_id', 'payment_id', 'name', 'email', 'contact');
	
	public function payment()
    {
		return $this->belongsTo('Payment');        
    }
}
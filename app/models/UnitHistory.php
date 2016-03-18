<?php 

class UnitHistory extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_unithistory';
	
	protected $fillable = array('unit_id', 'reservestate_id');	
	
	public function unit()
    {
		return $this->belongsTo('Unit');        
    }
}
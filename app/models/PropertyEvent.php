<?php 

class PropertyEvent extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_event';
	
	protected $fillable = array('title', 'desc', 'point', 'project_id', 'member_id');
	
	public function member()
    {
		return $this->belongsTo('Member');        
    }	
	
	public function project()
    {
		return $this->belongsTo('Project');        
    }
}
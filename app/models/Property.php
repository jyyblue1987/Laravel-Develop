<?php 

class Property extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_property';
	
	protected $fillable = array('title', 'desc', 'thumbnail', 'reportdate', 'gallery', 'video', 'addr', 'lat', 'lon', 
									'contact_title', 'contact_email', 'contact_no', 'contact_desc', 'state_id', 'website', 'url', 'location_url'  );
	
	public function state()
    {
		return $this->belongsTo('State');        
    }	
}
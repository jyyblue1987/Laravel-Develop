<?php 

class Subcategory extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_subcategory';
	
	protected $fillable = array('title', 'desc', 'source', 'thumbnail', 'category_id', 'start', 'end', 'url', 'sequence', 'state_id' );
	
	public function category()
    {
		return $this->belongsTo('Category');        
    }	
	public function state()
    {
		return $this->belongsTo('State');        
    }	
}
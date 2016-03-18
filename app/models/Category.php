<?php 

class Category extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_category';
	
	protected $fillable = array('title', 'desc', 'thumbnail', 'sequence', 'state_id' );
	
	public function state()
    {
		return $this->belongsTo('State');        
    }	
	
	public function subcategries()
    {
        return $this->hasMany('Subcategory');
    }
}
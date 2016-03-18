<?php 

class News extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_news';
	
	protected $fillable = array('title', 'desc', 'project_id', 'thumbnail', 'url', 'filetype_id');	
	
	public function project()
    {
		return $this->belongsTo('Project');
        //return $this->hasOne('Phone', 'foreign_key', 'local_key');
    }
	
	public function filetype()
    {
		return $this->belongsTo('FileType');
        //return $this->hasOne('Phone', 'foreign_key', 'local_key');
    }
}
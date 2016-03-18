<?php 

class Advertise extends Eloquent { 
	use SortableTrait;
	protected $table = 'wc_advertise';
	
	protected $fillable = array('title', 'link', 'thumbnail', 'startdate', 'enddate', 'sequence');
}
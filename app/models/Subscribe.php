<?php 

class Subscribe extends Eloquent { 	
	protected $table = 'wc_subscribe';
	
	protected $fillable = array('project_id', 'agent_id');	
}
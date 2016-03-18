<?php 

class ChatHistory  extends Eloquent { 
	protected $connection = 'chatserver';
	
	protected $table = 'ofmessagearchive';
	public $timestamps = false;
	
	protected $hidden = array('conversationID', 'fromJIDResource', 'toJIDResource');
}
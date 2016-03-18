<?php
define("UNKNOWN", "0"); 			// unknown
define("SUCCESS", "200"); 			// successfully

define("MISSING_PARAMETER", "100"); // Parameter missing
define("INVALID_PARAMETER", "101"); // Parameter is invacheckUserValiditylid

define("USER_EXIST", "201");		// user already exist
define("NO_VERIFIED", "202"); 		// not verified user
define("STATUS_INACTIVE", "203"); 	// status inactive

define("NO_USER_EXIST", "301"); 	// user not exist
define("INVALID_PASSWORD", "302");	// user or password is not valid
define("INVALID_VCODE", "303");		// verify code is invalid
define("NO_PERMISSIONS", "304"); 	// no permissions
define("EXPIRED_VCODE", "305");		// verify code is expired
define("SERVER_INTERNAL_ERROR", "401"); // server process error
define("CHAT_SERVER_ERROR", "402"); // chat server down


define("DEVICE_IPHONE", "iphone");	// device type iPhone
define("DEVICE_ANDROID", "android");// device type Android

require_once('openfire_api.php');
require_once('push_message.php');
require('twillo_service/Twilio.php');

function isNullOrEmptyString($question)
{
    return (!isset($question) || trim($question)==='');
}

class ProcessController extends BaseController
{
	function process($action)
	{	
		switch($action)
		{
			case 'getCountryList';
				$this->getCountryList();				
				break;
			case 'getAdvertiseList';
				$this->getAdvertiseList();				
				break;
			case 'getPropertyList';
				$this->getPropertyList();				
				break;
			case 'getCategoryList';
				$this->getCategoryList();				
				break;
			case 'getSubcategoryList';				
				$this->getSubcategoryList();				
				break;
			case 'getAllEventList';				
				$this->getAllEventList();				
				break;
			case 'login';
				$this->login();				
				break;
			case 'register';
				$this->register();				
				break;
			case 'updateMember';
				$this->updateMember();				
				break;
			case 'addEvent';
				$this->addEvent();				
				break;	
			case 'getEventList';
				$this->getEventList();				
				break;	
			case 'sendFeedback';
				$this->sendFeedback();				
				break;	
			case 'updateAgent';
				$this->updateAgent();				
				break;				
			case 'updateAdmin';
				$this->updateAdmin();				
				break;		
			case 'changePassword';
				$this->changePassword();				
				break;
			case 'getNewsList';
				$this->getNewsList();				
				break;
			case 'getProjectNewsList';
				$this->getProjectNewsList();				
				break;				
			case 'subscribeProject';
				$this->subscribeProject();				
				break;
			case 'getProjectList';
				$this->getProjectList();				
				break;
			case 'getAdminProjectNewsList';
				$this->getAdminProjectNewsList();				
				break;
			case 'getAgentList';	
				$this->getAgentList();
				return;
			case 'getUnitInfo';	
				$this->getUnitInfo();
				return;
			case 'reserveAgent';	
				$this->reserveAgent();
				return;
			case 'getUnitStatisticInfo';	
				$this->getUnitStatisticInfo();
				return;
			case 'getChatHistory';	
				$this->getChatHistory();
				return;	
			case 'uploadphoto';
				$this->uploadphoto();				
				break;			
			case 'push';
				$this->pushMessage();				
				break;
			case 'offline';
				$this->sendOfflineMessage();				
				break;
			case 'uploadfile';
				$this->uploadfile();				
				break;
		}		
	}		
	
	private function getCountryList()
	{
		$country = Country::all();
		
		$this->outputResult(SUCCESS, $country );			
	}
	
	private function getAdvertiseList()
	{
		$data = Advertise::orderBy('sequence', 'DESC')->get();
		
		$upload_path = Request::root() . '/uploads/file/';
		
		foreach($data as $key => $value)
		{
			$data[$key]['thumbnail'] =  $upload_path . $value['thumbnail'];
		}
		
		$this->outputResult(SUCCESS, $data );	
		
	}
	
	private function getPropertyList()
	{
		$data = Property::orderBy('updated_at', 'DESC')->get();
		
		$upload_path = Request::root() . '/uploads/file/';
		
		foreach($data as $key => $value)
		{
			$data[$key]['thumbnail'] =  $upload_path . $value['thumbnail'];
			
			$imgs = explode(", ", $value['gallery']);
			$gallery = "";											
			for($i = 0; $i < sizeof($imgs) ; $i++){
				
				if($imgs[$i] != ""){
					 $gallery = $gallery . $upload_path . $imgs[$i] . ", ";
				}
			}
			$data[$key]['gallery'] = $gallery;
			
			$videos = explode(", ", $value['video']);
			$video = "";											
			for($i = 0; $i < sizeof($videos) ; $i++){
				
				if($videos[$i] != ""){
					if (strpos($videos[$i], 'www.youtube.com') !== false) 
						$video = $video . $videos[$i] . ", ";
					else
						$video = $video . $upload_path . $videos[$i] . ", ";
				}
			}
			$data[$key]['video'] = $video;
		}
		
		$this->outputResult(SUCCESS, $data );	
	}
	
	private function getCategoryList()
	{
		$data = Category::orderBy('sequence')->get();
		
		$upload_path = Request::root() . '/uploads/file/';
		
		foreach($data as $key => $value)
		{
			$data[$key]['thumbnail'] =  $upload_path . $value['thumbnail'];
		}
		
		$this->outputResult(SUCCESS, $data );	
		
	}
	
	private function getSubcategoryList()
	{
		
		if( Input::has('category_id') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$category = Category::find(Input::get('category_id', '0'));
		if( empty($category) )
		{
			$this->outputResult(UNKNOWN, "Invalid Category");
			return;
		}
		$data = $category->subcategries()->orderBy('updated_at', 'DESC')->get();
		
		$upload_path = Request::root() . '/uploads/file/';
		
		foreach($data as $key => $value)
		{
			$data[$key]['thumbnail'] =  $upload_path . $value['thumbnail'];
		}
		
		$this->outputResult(SUCCESS, $data );	
		
	}
	
	private function getAllEventList()
	{
		$data = PropertyEvent::orderBy('updated_at', 'DESC')->get();
		
		$this->outputResult(SUCCESS, $data );	
	}

	private function register()
	{
		if( Input::has('username') == false ||
			Input::has('password') == false ||
			Input::has('email') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$type = Input::get('type', 'agent');
		$username = Input::get('username', '');
		$user = null;
		
		if( $type == 'member' )
			$user = Member::where('username' , '=', $username)->first();
		if( $type == 'agent' )
			$user = Agent::where('username' , '=', $username)->first();
		
		if( !empty($user) )	// user exist
		{
			$this->outputResult(USER_EXIST);
			return;
		}		
		
		$email = Input::get('email', '');
		
		$validator = Validator::make(
			array('email' => $email),
			array('email' => 'required|email')
		);
		
		if( $validator->fails() )
		{
			$this->outputResult(UNKNOWN, $validator->messages());
			return;
		}
		
		if( $type == 'member' )
			$user = new Member();
		if( $type == 'agent' )
			$user = new Agent();
		
		$user->username = $username;
		$user->fullname = $username;
		$user->password = Hash::make(Input::get('password', ''));
		$user->email = $email;
		$user->contact = Input::get('contact', '');					
		
		if( $type == 'agent' )
		{
			$user->passport = Input::get('passport', '');
			$user->address = Input::get('address', '');
			$user->postcode = Input::get('postcode', '');
			$user->city = Input::get('city', '');
			$user->country_id = Input::get('country_id', '107');
		}
		
		$user->device = Input::get('device', 'android');
		$user->pushkey = Input::get('pushkey');
		$user->token = Functions::generateRandomString(20);
		
		if( $user->save() == true )
			$this->outputResult(SUCCESS, $user );		
		else
			$this->outputResult(SERVER_INTERNAL_ERROR);	
	}
	
	private function login()
	{
		if( Input::has('username') == false ||
			Input::has('password') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$type = Input::get('type', 'agent');
		
		$username = Input::get('username', '');
		if( $type == 'member' )
			$user = Member::where('username' , '=', $username)->first();
		if( $type == 'agent' )
			$user = Agent::where('username' , '=', $username)->first();
		if( $type == 'admin' )
			$user = User::where('username' , '=', $username)->first();
		
		if( empty($user) )	// user not exist
		{
			$this->outputResult(NO_USER_EXIST);
			return;
		}	
		
		if( Hash::check(Input::get('password'), $user->password) == false )
		{
			$this->outputResult(INVALID_PASSWORD);
			return;
		}
		
		if( $type == 'agent' )
		{
			if( $user->state_id != 2 )
			{
				$this->outputResult(NO_VERIFIED);
				return;
			}			
		}
		
		if( $type == 'admin' || ($type == 'agent' && $user->state_id == 2) )
		{
			$openfire_id = 'ijm' . $type . '_' . $username;	
			$openfire_user = getUser($openfire_id);
			if( $openfire_user['code'] != 200 ) // not exist openfire server
				addUser($openfire_id, strrev($openfire_id));
		}
		
		$user->device = Input::get('device', 'android');
		$user->pushkey = Input::get('pushkey');
		$user->token = Functions::generateRandomString(20);
		
		if( $user->save() == true )
			$this->outputResult(SUCCESS, $user );		
		else
			$this->outputResult(SERVER_INTERNAL_ERROR);					
	}
	
	private function updateMember()
	{
		$this->update('member');
	}
	
	private function updateAgent()
	{
		$this->update('agent');
	}
	
	private function updateAdmin()
	{
		$this->update('admin');
	}
	
	private function update($type)
	{
		if( Input::has('id') == false ||
			Input::has('username') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		if( $type == 'member' )
			$user = Member::checkValidity();
		if( $type == 'agent' )
			$user = Agent::checkValidity();
		if( $type == 'admin' )
			$user = User::checkValidity();
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		$user->fullname = Input::get('username', '');
		$user->email = Input::get('email');
		$user->contact = Input::get('contact');
		
		
		if( $type == 'agent' )
		{
			$user->passport = Input::get('passport', '');
			$user->address = Input::get('address', '');
			$user->postcode = Input::get('postcode', '');
			$user->city = Input::get('city', '');
			$user->country_id = Input::get('country_id', '107');
		}
		
		if( $user->save() == true )
			$this->outputResult(SUCCESS, $user );		
		else
			$this->outputResult(SERVER_INTERNAL_ERROR);				
	}
	
	private function addEvent()
	{
	}
	
	private function getEventList()
	{
		if( Input::has('id') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$user = Member::checkValidity();
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		$this->outputResult(SUCCESS, $user->events );			
	}

	private function sendFeedback()
	{
		if( Input::has('id') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$user = Member::checkValidity();
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		if( empty($user) )
		{ 
			$this->outputResult(UNKNOWN, "Invalid Member");
			return;
		}
		
		// send email
		
		$this->outputResult(SUCCESS, '');			
	}
	
	private function changePassword()
	{
		if( Input::has('id') == false ||
			Input::has('newpass') == false ||
			Input::has('type') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$type = Input::has('type');
		
		$user = null;
		if( $type == 'member' )
			$user = Member::checkValidity();	
		if( $type == 'agent' )
			$user = Agent::checkValidity();	
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
			
		if( Hash::check(Input::get('password'), $user->password) == false )
		{
			$this->outputResult(INVALID_PASSWORD);
			return;
		}
		
		$user->password = Hash::make(Input::get('newpass'));
		
		if( $user->save() == true )
			$this->outputResult(SUCCESS, $user );		
		else
			$this->outputResult(SERVER_INTERNAL_ERROR);		
	}
	
	private function getNewsList()
	{
		if( Input::has('id') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$user = Agent::checkValidity();	
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		$projects = Project::orderBy('updated_at', 'DESC')->get();
		
		$upload_path = Request::root() . '/uploads/file/';
		
		$id = Input::get('id', '0');
		foreach( $projects as $key => $value )
		{
			$news = News::where('project_id', '=', $value->id)->orderBy('updated_at', 'DESC')->first();	
			$agent = $value->agent->find($id);			
			if( empty($agent) )
				$projects[$key]['subscribe'] = 0;
			else
				$projects[$key]['subscribe'] = 1;
			
			$projects[$key]['thumbnail'] =  $upload_path . $value['thumbnail'];
			$projects[$key]['floorplan'] =  $upload_path . $value['floorplan'];
			
			if( $news['filetype_id'] != '4' )
				$news['thumbnail'] = $upload_path . $news['thumbnail'];
			$projects[$key]['news'] = $news;			
		}
		
		$this->outputResult(SUCCESS, $projects );		
	}
	
	private function getProjectNewsList()
	{
		if( Input::has('id') == false ||
			Input::has('project_id') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$user = Agent::checkValidity();	
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		
		$project = Project::find(Input::get('project_id', '0'));
		if( empty($project) )
		{
			$this->outputResult(UNKNOWN, 'Invalid Project');
			return;
		}
		
		$data = $project->news()->orderBy('updated_at', 'DESC')->get();
		$upload_path = Request::root() . '/uploads/file/';
		foreach( $data as $key => $value )
		{
			$data[$key]['thumbnail'] =  $upload_path . $value['thumbnail'];			
		}
		
		$this->outputResult(SUCCESS, $data );		
	}
	

	
	
	private function subscribeProject()
	{
		if( Input::has('id') == false ||
				Input::has('project_id') == false	)
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$user = Agent::checkValidity();	
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		$project_id = Input::get('project_id', '0');
				
		$project = Project::find($project_id);
		if( empty($project) )
		{ 
			$this->outputResult(UNKNOWN, "Not valid Project");
			return;
		}
		
		$id = Input::get('id', '0');
		
		$exist = DB::table('wc_subscribe')
						->where('agent_id', '=', $id)
						->where('project_id', '=', $project_id)
						->exists();
		if( $exist )
		{ 
			$this->outputResult(UNKNOWN, "You have already subscribed on this project.");
			return;
		}
		
		$subscribe = new Subscribe();

		$subscribe->agent_id = $id;
		$subscribe->project_id = $project_id;
		
		$subscribe->save();		
		
		$this->outputResult(SUCCESS, $project );		
	}
	
	private function getProjectList()
	{
		if( Input::has('id') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$user = Agent::checkValidity();	
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		$projects = $user->projects()->orderBy('updated_at', 'DESC')->get();
		
		foreach( $projects as $value )
		{
			$value->user;
		}
		
		$this->outputResult(SUCCESS, $projects );		
	}
	
	private function getAdminProjectNewsList()
	{
		if( Input::has('id') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$user = User::checkValidity();	
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		$projects = $user->projects()->orderBy('updated_at', 'DESC')->get();
		$upload_path = Request::root() . '/uploads/file/';
		foreach( $projects as $key => $value )
		{
			$projects[$key]['thumbnail'] =  $upload_path . $value['thumbnail'];
			$projects[$key]['floorplan'] =  $upload_path . $value['floorplan'];
			
			$news = News::where('project_id', '=', $value->id)->orderBy('updated_at', 'DESC')->first();	
			$news['thumbnail'] = $upload_path . $news['thumbnail'];
			$projects[$key]['news'] = $news;			
		}
		
		$this->outputResult(SUCCESS, $projects );		
	}
	
	private function getAgentList()
	{
		if( Input::has('id') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$user = User::checkValidity();	
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		// Note
		// follow 3 query, IN, JOIN, EXIST must be compared with performance
		// IN QUERY 
		$query = 'SELECT * FROM wc_agent WHERE wc_agent.id IN (SELECT DISTINCT agent_id FROM wc_subscribe INNER JOIN wc_project ON project_id = wc_project.id WHERE wc_project.user_id = ' . $user->id . ')';
		
		// MULTI JOIN QUERY
		$query = 'SELECT DISTINCT wc_agent.*
					FROM wc_subscribe
						JOIN wc_project
							ON wc_subscribe.project_id = wc_project.id
						JOIN wc_agent
							ON wc_subscribe.agent_id = wc_agent.id  
					WHERE wc_project.user_id = ' . $user->id;		
					
		// $agents = DB::table('wc_subscribe')
			// ->join('wc_project', 'wc_subscribe.project_id', '=', 'wc_project.id')
			// ->join('wc_agent', 'wc_subscribe.agent_id', '=', 'wc_agent.id')
			// ->where('wc_project.user_id', '=', $user->id)
			// ->select('wc_agent.*')
			// ->distinct()
			// ->get();
					
		// EXISTS QUERY
		$query = 'SELECT * FROM wc_agent WHERE EXISTS (SELECT 1 FROM wc_subscribe WHERE wc_agent.id = wc_subscribe.agent_id AND EXISTS(SELECT 1 FROM wc_project WHERE wc_project.id = wc_subscribe.project_id AND wc_project.user_id =' . $user->id .'))';			

		//$agents = DB::select(DB::raw($query));
		
		$admin_id = $user->id;
		
		$agents = DB::table('wc_agent')
            ->whereExists(function($query) use ($admin_id) 
            {
				$query->select(DB::raw(1))
                      ->from('wc_subscribe')
                      ->whereRaw('wc_subscribe.agent_id=wc_agent.id')
					  ->whereExists(function($query) use ($admin_id) 
							{
								
								$query->select(DB::raw(1))
									  ->from('wc_project')
									  ->whereRaw('wc_project.id = wc_subscribe.project_id')
									  ->where('wc_project.user_id', '=', $admin_id);
							});
            })
            ->get();
		
				
		$this->outputResult(SUCCESS, $agents );		
	}
	
	private function getUnitInfo()
	{
		if( Input::has('id') == false ||
			Input::has('project_id') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$user = User::checkValidity();	
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		$project_id = Input::get('project_id', '0');
		$project = $user->projects()->find($project_id);
		if( empty($project) )
		{
			$this->outputResult(UNKNOWN, 'Invalid Project');
			return;
		}
		
		$units = $project->units;
		
		$this->outputResult(SUCCESS, $units );		
	}
	
	private function reserveAgent()
	{
		if( Input::has('id') == false ||
			Input::has('unit_id') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$user = User::checkValidity();	
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		$unit_id = Input::get('unit_id', '0');
		$unit = Unit::find($unit_id);
		
		if( empty($unit) )
		{
			$this->outputResult(UNKNOWN, 'Invalid Unit');
			return;
		}
		
		if($unit->reservestate_id > 1)
		{
			$this->outputResult(UNKNOWN, 'This unit has been already reserved');
			return;
		}
		
		$reserve = new Reserve();
		
		$reserve->payment_id = Input::get('payment_id', '0');
		$reserve->name = Input::get('name', '');
		$reserve->email = Input::get('email', '');
		$reserve->contact = Input::get('contact', '');
		
		$reserve->save();
		
		$unit->reserve_id = $reserve->id;
		$unit->reservestate_id = 2;
		
		$unit->save();		
		
		$this->outputResult(SUCCESS, $reserve );	
	}
	private function getUnitStatisticInfo()
	{
		if( Input::has('id') == false ||
			Input::has('project_id') == false )
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$user = User::checkValidity();	
		
		if( empty($user) )
		{ 
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		$project_id = Input::get('project_id', '0');
		$project = $user->projects()->find($project_id);
		if( empty($project) )
		{
			$this->outputResult(UNKNOWN, 'Invalid Project');
			return;
		}
		
		$history = UnitHistory::where('project_id', '=', $project_id)->orderBy('created_at')->get();
		
		$statistic = array();
		
		$statistic['available'] = $history->lists('available_count');
		$statistic['reserved'] = $history->lists('reserved_count');
		$statistic['sold'] = $history->lists('sold_count');
					
		$this->outputResult(SUCCESS, $statistic );	
	}
	
	private function getChatHistory()
	{
		if( Input::has('id') == false ||
			Input::has('type') == false ||
			Input::has('to') == false 
			)
		{ 
			$this->outputResult(MISSING_PARAMETER);
			return;
		}
		
		$id = Input::get('id', '0');
		$type = Input::get('type', 'admin');
		$toid = Input::get('to', '0');
		
		$senddate = Input::get('senddate', '0');
		
		$user = null;
		if( $type == 'admin' )
			$user = User::checkValidity();
		if( $type == 'agent' )
			$user = Agent::checkValidity();
		
		if( empty($user) )
		{
			$this->outputResult(NO_PERMISSIONS);
			return;
		}
		
		$chat_id = $user->username;
		$touser = null;
		
		if( $type == 'admin' )
		{
			$chat_id = 'ijmadmin_' . $chat_id;
			$touser = Agent::find($toid);
		}
		if( $type == 'agent' )
		{
			$chat_id = 'ijmagent_' . $chat_id;
			$touser = User::find($toid);
		}
		
		if( empty($touser) )
		{
			$this->outputResult(UNKNOWN, 'Invalid User');
			return;
		}
		
		$tochat_id = $touser->username;
		
		if( $type == 'admin' )
			$tochat_id = 'ijmagent_' . $tochat_id;			
		if( $type == 'agent' )
			$tochat_id = 'ijmadmin_' . $tochat_id;			
		
		$chatserver_address = '@' . Config::get('constants.chat_address');
		
		$chat_id = $chat_id . $chatserver_address;
		$tochat_id = $tochat_id . $chatserver_address;
		
		$idwhere = '(fromJID = \'' . $chat_id . '\' and toJID = \'' . $tochat_id . '\') or (fromJID = \'' . $tochat_id . '\' and toJID = \'' . $chat_id . '\')';
		
		$query = ChatHistory::where(function($query) use ($idwhere)
            {
                $query->whereRaw($idwhere);							
            });    
		
		if( $senddate != '0' )
		{
			$query->where('sentDate', '<', $senddate);		
		}
		
		$chathistory = $query->orderBy('sentDate', 'DESC')->take(20)->get();
		
					
		$this->outputResult(SUCCESS, array_reverse($chathistory->toArray()) );	
	}
	
	private function uploadphoto()
	{
		$user = $this->checkUserValidity();
		if($user === null)
			return;
		
		$filekey = 'myfile';
		if(Input::hasFile($filekey) === false )
		{
			$this->outputResult( MISSING_PARAMETER );
			return;
		}
		
		if (Input::file($filekey)->isValid() === false )
		{
			$this->outputResult( INVALID_PARAMETER );
			return;
		}
		
		$file_ext	= pathinfo($_FILES[$filekey]["name"], PATHINFO_EXTENSION);
		$file_name = $user->username . '_' . time() . '.' . $file_ext;
		
		$ret = Input::file($filekey)->move("uploads/", $file_name);
		
		if( empty($ret) )
			$this->outputResult(SERVER_INTERNAL_ERROR, 'There was an error uploading your file.');
			
		if( $user->avastar != '' )
		{
			$old_filepath = $this->getPhotoFilePath( $user->avastar );
			
			if( file_exists($old_filepath) )
				unlink( $old_filepath );
		}

		$user->avastar = $file_name;
		if( !$user->save() )
			$this->outputResult(SERVER_INTERNAL_ERROR, "Cannot save file" );

		$this->outputResult(SUCCESS, $file_name );
	}
	
	public function getPhotoFilePath( $file_name )
	{
		$dir_path = "uploads/";
		$file_path = $dir_path . $file_name;

		return $file_path;
	}
	
	private function uploadfile()
	{
		$user = $this->checkUserValidity();
		if($user === null)
			return;
		
		$filekey = 'myfile';
		if(Input::hasFile($filekey) === false )
		{
			$this->outputResult( MISSING_PARAMETER );
			return;
		}
		
		if (Input::file($filekey)->isValid() === false )
		{
			$this->outputResult( INVALID_PARAMETER );
			return;
		}
		
		$file_name = $_FILES[$filekey]["name"];
	
		if( !Input::file($filekey)->move("uploads/", $file_name) )
			$this->outputResult(SERVER_INTERNAL_ERROR, 'There was an error uploading your file.');
		
		$this->outputResult(SUCCESS, $file_name );
	}
	
	
	private function outputResult( $retcode, $content = '', $error_msg = null )
	{
		// header('Content-type: application/json');

		if( $error_msg == null )
		{
			switch ($retcode)
			{
			case SUCCESS:
				$error_msg = '';
				break;
			case MISSING_PARAMETER:
				$error_msg = 'Parameter is missing';
				break;
			case INVALID_PARAMETER:
				$error_msg = 'Parameter is invalid';
				break;
			case USER_EXIST:
				$error_msg = 'User is already exist';
				break;
			case NO_USER_EXIST:
				$error_msg = 'User is not exist';
				break;
			case INVALID_PASSWORD:
				$error_msg = 'Your input password is not correct';
				break;
			case INVALID_VCODE:
				$error_msg = 'Verification code is invalid';
				break;
			case EXPIRED_VCODE:
				$error_msg = 'Verification code is expired';
				break;
			case STATUS_INACTIVE:
				$error_msg = 'You can not login, you are disabled by administrator';
				break;
			case NO_VERIFIED:
				$error_msg = 'You are not verified yet.';
				break;
			case NO_PERMISSIONS:
				$error_msg = 'You have no permission';
				break;
			case SERVER_INTERNAL_ERROR:
				$error_msg = 'Server internal process error.';
				break;
			case CHAT_SERVER_ERROR:
				$error_msg = 'Chat server is not responding.';
				break;
			default :
				$error_msg = $content;
				break;
			}
		}

		$response = array( 'retcode'=>$retcode, 'content'=>$content, 'error_msg'=>$error_msg );

		echo json_encode($response);		
	}
	
	private function pushMessage()
	{
		$message = Input::get('message');
		if( empty($message) )
			return;
		
		$android_users = User::where('device', '=', '1')->get();
		$ios_users = User::where('device', '=', '2')->get();
		
		$gcm_key = array();
		foreach( $android_users as $android )
		{
			$pushkey = $android->pushkey;
			if( empty($pushkey) )
				continue;
			$gcm_key[] = $pushkey;
		}		
		
		$apn_key = array();
		foreach( $ios_users as $ios )
		{
			$pushkey = $ios->pushkey;
			if( empty($pushkey) )
				continue;
			$apn_key[] = $pushkey;
		}		
		
		$ret1 = push2Android($gcm_key, $message);
		$ret2 = push2IPhone($apn_key, $message);		
	}
	
	private function sendOfflineMessage()
	{
		$lastid = PushTask::max('lastid');
		
		if( empty($lastid) )
			$messages = OfflineMessage::all()->take(1000)->get();
		else			
			$messages = OfflineMessage::where('messageID' , '>', $lastid)->take(1000)->get();
		
        $maxid = $lastid;		
		foreach( $messages as $msg )
		{
			$pushkey = $msg->user['pushkey'];
			$content = $msg->stanza;
			
			if( empty($pushkey) )
				continue;
			
			$id = $msg->messageID;
			if( $id > $maxid )
				$maxid = $id;
			if( $msg->user['device'] === 1 ) // android
			{
				$gcm_key = array();
				$gcm_key[] = $pushkey;
				push2Android($gcm_key, $content);
			}
			else if( $msg->user['device'] === 2 ) // iOS
			{
				$apn_key = array();
				$apn_key[] = $pushkey;
				push2IPhone($apn_key, $content);
			}
		}
		
		$task = new PushTask();
		$task->lastid = $maxid;
		$task->save();
	}
}

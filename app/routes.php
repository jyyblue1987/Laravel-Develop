<?php

session_start();

Route::get('/', function(){
	return Redirect::to('/user');
});

Route::get('/login',array('as'=>'login','uses'=>'UserController@login'));
Route::post('/postLogin',array('as'=>'login','uses'=>'UserController@postLogin'));
Route::get('/logout',array('uses'=>'UserController@logout'));


Route::get('/search', array('uses'=>'UserController@search'));
Route::get('/update', array('uses'=>'UserController@update'));
Route::any('/email', 'UserController@email');
Route::get('/userprofile', 'UserController@userprofile');

Route::group(['before' => 'api.auth'], function() {
    // User 
	Route::resource('user', 'UserController');
	Route::get('/user/delete/{id?}', array('uses'=>'UserController@destroy'));

	// agent 
	Route::resource('agent', 'AgentController' );
	Route::get('/agent/delete/{id?}', array('uses'=>'AgentController@destroy'));

	// member 
	Route::resource('member', 'MemberController' );
	Route::get('/member/delete/{id?}', array('uses'=>'MemberController@destroy'));


	// news 
	Route::resource('news', 'NewsController' );
	Route::get('/news/delete/{id?}', array('uses'=>'NewsController@destroy'));

	// project 
	Route::resource('project', 'ProjectController' );
	Route::get('/project/delete/{id?}', array('uses'=>'ProjectController@destroy'));

	// unit 
	Route::resource('unit', 'UnitController' );
	Route::get('/unit/delete/{id?}', array('uses'=>'UnitController@destroy'));

	// event 
	Route::resource('event', 'EventController' );
	Route::get('/event/delete/{id?}', array('uses'=>'EventController@destroy'));

	// advertise
	Route::resource('advertise', 'AdvertiseController' );
	Route::get('/advertise/delete/{id?}', array('uses'=>'AdvertiseController@destroy'));

	// category
	Route::resource('category', 'CategoryController' );
	Route::get('/category/delete/{id?}', array('uses'=>'CategoryController@destroy'));

	// subcategory
	Route::resource('subcategory', 'SubcategoryController' );
	Route::get('/subcategory/delete/{id?}', array('uses'=>'SubcategoryController@destroy'));

	// Property
	Route::resource('property', 'PropertyController' );
	Route::get('/property/delete/{id?}', array('uses'=>'PropertyController@destroy'));

	// Push
	Route::resource('push', 'PushController' );
	Route::get('/push/delete/{id?}', array('uses'=>'PushController@destroy'));
	Route::get('/push/send/{id?}', array('uses'=>'PushController@send'));
});

// Rest API
Route::any('/process/{id?}', 'ProcessController@process'); 

// file upload
Route::any('/upload', array('uses'=>'UploadController@upload'));
<?php

class NewsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// delete action
		$ids = Input::get('ids');
		if( !empty($ids) )
		{
			DB::table('wc_news')->whereIn('id', $ids)->delete();			
			return Redirect::back()->withInput();				
		}
/*
		for($i = 0; $i < 100; $i++)
		{	
			$news = new News();
			$news->title = 'news title ' . $i;
			$news->desc = 'Desc '  . $i;
			$news->project_id = 1;			
			$news->save();
		}	
*/	
		
		$query = News::sortable();
		
		$search = Input::get('search');
		if( !empty($search) )
		{
			$query->where(function($searchquery)
				{
					$search = '%' . Input::get('search') . '%';
					$searchquery->where('title', 'like', $search)						
							->orWhere('desc', 'like', $search);
				});	
		}
		
		$project_id = Input::get('project_id');
		if( !empty($project_id) && $project_id != 0 )
		{
			$query->where(function($searchquery)
				{
					$project_id = Input::get('project_id');
					$searchquery->where('project_id', '=', $project_id);
				});	
		}
		

		$pagesize = Input::get('pagesize');
		if( empty($pagesize) )
			$pagesize = 10;		
		
		$news = $query->paginate($pagesize);	
		
		Input::flashOnly('search', 'project_id');
		
		$projects = ['0' => '-- Select Project --'] + User::projectlist();
		
		return View::make('news.list')->with('news', $news)
											->with('projects', $projects)
											->with('pagesize',$pagesize);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$news = new News();
		$news->thumbnail = "default_thumbnail.jpg";
		$news->filetype_id = 1;
		
		$projects = User::projectlist();
		$filetype = FileType::lists('name', 'id');
		
		return View::make('news.form')->with('news', $news)
								->with('filetype', $filetype)
								->with('projects', $projects);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$value = Input::all();
		
		if( $value['filetype_id'] == '4' ) 
		{
			$video_id = '';
			if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $value['url'], $match)) {
				$video_id = $match[1];
			}

			$thumbnail = "http://i1.ytimg.com/vi/". $video_id . "/default.jpg";
			$value['thumbnail'] = $thumbnail;			
		}		
		
		$news = News::create($value);
		if( empty($news) )
		{
			$message = 'Internal Server error';
			return Redirect::back()
					->withErrors('message', $message)
					->withInput();
		}	
		
		$message = 'SUCCESS';	
		
		return Redirect::back()
						->withErrors([$message])
						->withInput();
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$news = News::find($id);				
		return View::make('news.view')->with('news', $news);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$news = News::find($id);	
		$projects = User::projectlist();		
		$filetype = FileType::lists('name', 'id');
		return View::make('news.form')->with('news', $news)
										->with('filetype', $filetype)
										->with('projects', $projects);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$value = Input::all();

		if( $value['filetype_id'] == '4' ) 
		{
			$video_id = '';
			if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $value['url'], $match)) {
				$video_id = $match[1];
			}

			$thumbnail = "http://i1.ytimg.com/vi/". $video_id . "/default.jpg";
			$value['thumbnail'] = $thumbnail;			
		}	

		$news = News::find($id);
		
		if (!$news->update($value)) {
			return Redirect::back()
					->withErrors('message', $message)
					->withInput();
		}	
		
		$message = 'SUCCESS';
		
		
		return Redirect::back()
						->withErrors([$message])
						->withInput();		
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$news = News::find($id);
		$news->delete();

		return Redirect::back();
		
	}


}

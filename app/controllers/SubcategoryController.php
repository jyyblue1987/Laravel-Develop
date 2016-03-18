<?php

class SubcategoryController extends BaseController {

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
			DB::table('wc_subcategory')->whereIn('id', $ids)->delete();			
			return Redirect::back()->withInput();				
		}

		$query = Subcategory::sortable();
		
		$search = Input::get('search');
		if( !empty($search) )
		{
			$query->where(function($searchquery)
				{
					$search = '%' . Input::get('search') . '%';
					$searchquery->where('title', 'like', $search)
					 ->orWhere('desc', 'like', $search)
					 ->orWhere('source', 'like', $search);
				});	
		}
		
		$category_id = Input::get('category_id');
		if( !empty($category_id) && $category_id != 0 )
		{
			$query->where(function($searchquery)
				{
					$category_id = Input::get('category_id');
					$searchquery->where('category_id', '=', $category_id);
				});	
		}
					
					
		$pagesize = Input::get('pagesize');
		if( empty($pagesize) )
			$pagesize = 10;		
		
		$category = ['0' => '-- Select Category --'] + Category::lists('title', 'id');
		$subcategory = $query->paginate($pagesize);	
		
		Input::flashOnly('search', 'category_id');
				
		return View::make('category.subcategory.list')->with('subcategory', $subcategory)	
											->with('category', $category)		
											->with('pagesize', $pagesize);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$subcategory = new Subcategory();

		$subcategory->state_id = 0;
		$subcategory->thumbnail = "default_thumbnail.jpg";
		$subcategory->start = date('Y-m-d', time());
		$subcategory->end = date('Y-m-d', time());
		
		$category = Category::lists('title', 'id');
	
		return View::make('category.subcategory.form')->with('subcategory', $subcategory)
														->with('category', $category);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$subcategory = Subcategory::create(Input::all());
		if( empty($subcategory) )
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
		$subcategory = Subcategory::find($id);				
		return View::make('category.subcategory.view')->with('subcategory', $subcategory);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$subcategory = Subcategory::find($id);	
		
		return View::make('category.subcategory.form')->with('subcategory', $subcategory);										
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$subcategory = Subcategory::find($id);
			
		if (!$subcategory->update(Input::all())) {
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
		$subcategory = Subcategory::find($id);
		$subcategory->delete();

		return Redirect::back();
		
	}


}

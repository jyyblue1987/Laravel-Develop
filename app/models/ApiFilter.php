<?php
class ApiFilter {
 
	public function filter()
	{
		if( !isset($_SESSION['admin'] ) ) {
		    if (Request::ajax())
			{
				return Response::make('Unauthorized', 401);
			}
			else
			{
				return Redirect::guest('login');
			}
		}
	}
 
}
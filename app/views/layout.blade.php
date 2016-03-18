<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

@include('header')

<body>
    
	<script type='text/javascript'>
		$(document).ready(function() {        
			
		});
	</script>
	<div id="ajax_loading_box" class="ajax-loading-box">
		<div id="ajax_loading_message" class="ajax-inner-loading-box">Loading...</div>
	</div>

	@include('navmenu')

	@yield('content')

</body>
</html>

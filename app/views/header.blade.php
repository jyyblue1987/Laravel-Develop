@section('header')
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="/css/main.css" />
	<link rel="stylesheet" type="text/css" href="/css/form.css" />
        
	<link rel="stylesheet" type="text/css" href="/css/styles.css" />
    <link rel="stylesheet" type="text/css" href="/css/mystyle.css" />
    <link rel="stylesheet" type="text/css" href="/css/token-input.css" />
    <link rel="stylesheet" type="text/css" href="/css/token-input-facebook.css" />
    <link rel="stylesheet" type="text/css" href="/css/jqueryui.css" />
	<link rel="stylesheet" type="text/css" href="/css/uploadfile.css" />

	
	<script type='text/javascript' src='/js/jquery.min.js'></script>

	<script type='text/javascript' src='/js/jquery.appear-1.1.1.js'></script>
	<script type='text/javascript' src='/js/tooltip.min.js'></script>

	<script type='text/javascript' src='/js/tinymce.editor.js'></script>

	<script type='text/javascript' src='/js/core.js'></script>
	<script type='text/javascript' src='/js/ajax.js'></script>
	<script type='text/javascript' src='/js/funcs.js'></script>	
	<script type='text/javascript' src='/js/jquery.uploadfile.min.js'></script>

	<script type='text/javascript'>
        var changes_warning = 'Y';
        $(function(){
                $.runCart('A');
        });   


		$(document).ready(function() {
		  $(window).keydown(function(event){
			if( (event.keyCode == 13) && (validationFunction(event) == false) ) {
			  event.preventDefault();
			  return false;
			}
		  });
		});		
    </script>

	<link rel="shortcut icon" href="/css/images/healthcare.ico" />
	<link rel="stylesheet" type="text/css" href="/assets/7b8be64/gridview/styles.css" />
	<link rel="stylesheet" type="text/css" href="/assets/bda30421/pager.css" />
	<script type="text/javascript" src=""></script>
	<script type="text/javascript" src="/assets/89e1c5f0/jquery.ba-bbq.js"></script>
	
	<title>
		Whatscomingtokl
	</title>
</head>
@show

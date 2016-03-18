@extends('layout')
@section('content')
<div id="main_column" class="clear">
	<div><img src=""></div>
	<div class="clear mainbox-title-container">
			<h1 class="mainbox-title float-left">
			Contact: <?php echo $user['contact'];?>    </h1>
	</div>
	<div class="mainbox-body">
		<div class="cm-tabs-content">
			<form class="cm-form-highlight">
				<h2 class="subheader">
					Member information
				</h2>

				<table class="info_detail_view">
				<tr><td class="title">Name&nbsp;&nbsp;&nbsp;:</td><td class="value">{{$user['fullname']}}</td></tr>
				<tr><td class="title">Email&nbsp;&nbsp;&nbsp;:</td><td class="value">{{$user['email']}}</td></tr>		
				<tr><td class="title">Collected Pointed&nbsp;&nbsp;&nbsp;:</td><td class="value">{{$user['point']}}</td></tr>		
				<tr><td class="title">Status&nbsp;&nbsp;&nbsp;:</td><td class="value">{{$user->state['name']}}</td></tr></table>				
						
				<div class="buttons-container buttons-bg cm-toggle-button">
					<span class="cm-button-main cm-process-items">
						<input type="button" onclick="location.href = '/member'"  value="Back" />
					</span>
				</div>
			</form>
		</div>
	</div>
	
</div>

@stop

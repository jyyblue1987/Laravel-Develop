@extends('layout')
@section('content')
<div id="main_column" class="clear">
	<?php
		if( $errors->any() )
		{
			$message = $errors->first();
			if( $message === 'SUCCESS' )
				Functions::alertSuccessMessage('');
			else if( $message !== '' )
				Functions::alertErrorMessage ($message);	
		}		
	?>
	<div><img src=""></div>
	<div class="clear mainbox-title-container">
			<h1 class="mainbox-title float-left">
			Contact: <?php echo $user['contact'];?>    </h1>
	</div>
	<div class="mainbox-body">
		<div class="cm-tabs-content">
			{{ Form::open(array('url' => 'agent/'. $user->id, 'method' => 'put' )) }}
				<h2 class="subheader">
					Agent information
				</h2>

				<table class="info_detail_view">
					<tr><td class="title">Name&nbsp;&nbsp;&nbsp;:</td><td class="value">{{$user['fullname']}}</td></tr>
					<tr><td class="title">Email&nbsp;&nbsp;&nbsp;:</td><td class="value">{{$user['email']}}</td></tr>		
					<tr><td class="title">New IC/Passport&nbsp;&nbsp;&nbsp;:</td><td class="value">{{$user['passport']}}</td></tr>						
					<tr><td class="title">PostCode&nbsp;&nbsp;&nbsp;:</td><td class="value">{{$user['postcode']}}</td></tr>
					<tr><td class="title">Address&nbsp;&nbsp;&nbsp;:</td><td class="value">{{$user['address']}}</td></tr>				
					<tr><td class="title">City&nbsp;&nbsp;&nbsp;:</td><td class="value">{{$user['city']}}</td></tr>
					<tr><td class="title">Country&nbsp;&nbsp;&nbsp;:</td><td class="value">{{$user->country['name']}}</td></tr>
					<fieldset>
						<tr><td class="title">Status&nbsp;&nbsp;&nbsp;:</td><td class="value"><?php echo Form::select('state_id', $state, $user->state_id); ?></td></tr>
					</fieldset>		
				</table>		
				<div class="buttons-container buttons-bg cm-toggle-button">
					<span  class="submit-button cm-button-main ">
						<input type="submit" name="dispatch[profiles.update]" value="Save" onclick="onSubmit()" />
					</span>
					&nbsp;&nbsp;&nbsp;
					<span class="cm-button-main cm-process-items">
						<input type="button" onclick="location.href = '/agent'"  value="Cancel" />
					</span>
				</div>
			</form>
		</div>
	</div>
	
</div>

@stop

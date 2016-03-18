@extends('layout')
@section('content')

<?php
	
	$method = "post";								
	$create = 'Create';
	$title = "Add Property";
	if( !empty($user->id) )
	{
		$method = "put";								
		$create = "Save";								
		$title = "Update Property";
	}

?>
	
<table cellpadding="0" cellspacing="0" border="0" class="content-table">
    <tr valign="top">
        <td width="1px" class="side-menu">
            <div id="right_column">

            </div>
        </td>
        <td class="content">

            <div class="mainbox-breadcrumbs">
                &nbsp;
            </div>

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
                <script type="text/javascript" src="/js/profiles_scripts.js"></script>

                <div>
                    <h1 class="mainbox-title">
					{{$title}}
                    </h1>
                    <div class="mainbox-body" >

                        <script type="text/javascript" src="/js/tabs.js"></script>

                        <!--                        <div class="tabs cm-j-tabs cm-track">
                                                    <ul>
                                                        <li id="general" class="cm-js"><a >General</a></li>
                                                    </ul>
                                                </div>-->
                        <div class="cm-tabs-content">							
							{{ Form::open(array('url' => 'user/'. $user->id, 'method' => $method )) }}
                                <div id="content_general">
                                    <fieldset>
                                        <h2 class="subheader">
                                            User account information
                                        </h2>

                                        <div class="form-field">
                                            <label for="fullname" class="cm-required">Name:</label>
                                            <input type="text" id="fullname" name="fullname" class="input-text" size="32" maxlength="50" value="{{$user->fullname}}" />											
                                        </div>
                                        
                                        <div class="form-field">
                                            <label for="email" class="cm-required cm-email">Email:</label>
                                            <input type="text" id="email" name="email" class="input-text" size="32" maxlength="128" value="{{$user->email}}" />
                                        </div>

                                        <div class="form-field">
                                            <label for="contact" class="cm-required">Contact No:</label>
                                            <input type="text" id="contact" name="contact" class="input-text" size="32" maxlength="50" value="{{$user->contact}}" />
                                        </div>

                                        <div class="form-field">
                                            <label for="password" class="cm-required">Password:</label>
                                            <input type="password" id="password" name="password" class="input-text cm-autocomplete-off" size="32" maxlength="32" value="" />
                                        </div>

                                        <div class="form-field">
                                            <label for="password2" class="cm-required">Confirm password:</label>
                                            <input type="password" id="password2" name="password2" class="input-text cm-autocomplete-off" size="32" maxlength="32" value="" />
                                        </div>
          

                                        <div class="form-field">
                                            <label class="cm-required">Status:</label>
                                            <div class="select-field">
                                                <input type="radio" name="state_id" id="user_data_0_a" <?php
												
                                                if (intval($user->state_id) === 1)
                                                {
                                                    echo 'checked="checked"';
                                                }
                                                ?> value="1" class="radio" /><label for="user_data_0_a">Active</label>
                                                <input type="radio" name="state_id" id="user_data_0_d" <?php
                                                if (intval($user->state_id) === 0)
                                                {
                                                    echo 'checked="checked"';
                                                }
                                                ?> value="0" class="radio" /><label for="user_data_0_d">Inactive</label>
                                            </div>											
                                        </div>
										
										
                                    </fieldset>

                                </div>

<!--                                <p class="select-field notify-customer cm-toggle-button">
    <input type="checkbox" name="notify_customer" value="Y" checked="checked" class="checkbox" id="notify_customer" />
    <label for="notify_customer">Notify user</label>
</p>-->
							
                                <div class="buttons-container buttons-bg cm-toggle-button">
                                    <span  class="submit-button cm-button-main ">
                                        <input type="submit" name="dispatch[profiles.update]" value="{{$create}}" />
                                    </span>
                                    &nbsp;&nbsp;&nbsp;
                                    <span class="cm-button-main cm-process-items">
                                        <input type="button" onclick="location.href = '/user';"  value="Cancel" />
                                    </span>
                                </div>

                            </Form>
                        </div>
                    </div>
                </div>
                <!--main_column-->
            </div>
        </td>
    </tr>
</table>
@stop

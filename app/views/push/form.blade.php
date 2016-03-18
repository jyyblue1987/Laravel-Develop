@extends('layout')
@section('content')

<?php
	
	$method = "post";								
	$create = 'Create';
	$title = "Add Push";
	if( !empty($push->id) )
	{
		$method = "put";								
		$create = "Save";								
		$title = "Update Push";
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
							{{ Form::open(array('url' => 'push/'. $push->id, 'method' => $method )) }}
                                <div id="content_general">
                                    <fieldset>
                                        <h2 class="subheader">
                                            Push information
                                        </h2>
                                        <input type="hidden" id="user_id" name="user_id" value="{{$push->user_id}}" />										
										<div class="form-field">
                                            <label for="message" class="cm-required">Broadcast Msg:</label>
                                            <textarea name="message" id="message" cols="55" rows="4" class="input-textarea-long">{{$push->message}}</textarea>
                                            &nbsp;<div><font color="blue">( Best max: 500 characters )</font></div>

                                        </div>
										
										<input type="hidden" id="pushgroup_id" name="class" value="{{$push->class}}" />
										<div class="form-field">
                                            <label for="desc" class="cm-required">Group:</label>
											<?php echo Form::select('pushgroup_id', $group, $push->pushgroup_id, array('id' => 'pushgroup')); ?>
                                        </div>
										
										<script type="text/javascript" src="/js/multi.select.js"></script>
										<input type="hidden" id="category_ids" name="class" value="{{$push->class}}" />
										<div class="form-field" id="project_select">
                                            <label for="category_src" class="cm-required">Project Group:</label>
                                            <!--                                            <div class="select-field">
                                                                                            <input type="radio" name="item_data[category_type]" id="category_data_0_a" checked="checked" value="1" class="radio" />
                                                                                            <label for="item_data_0_a">All</label>
                                                                                            <input type="radio" name="item_data[category_type]" id="category_data_0_d" value="0" class="radio" />
                                                                                            <label for="item_data_0_d">Select</label>
                                                                                        </div>-->
                                            <div class="select-field">
                                                <table>
                                                    <tr>
                                                        <td style="text-align:center"><b>Source</b></td>
                                                        <td>&nbsp;</td>
                                                        <td style="text-align:center"><b>Selected</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <SELECT id="category_source" style="width: 150px;" size="10" multiple>
                                                                <?php foreach ($projects as $v1) : ?>															
                                                                    <option value="<?php echo $v1["id"] ?>">
                                                                        <?php echo $v1["name"] ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </SELECT>
                                                        </td>
                                                        <td>
                                                            <a href="#" onclick="listbox_move_all('category_source', 'category_selected', 1, 'category_ids')"> >>>> </a><br/>
                                                            <a href="#" onclick="listbox_move('category_source', 'category_selected', 1, 'category_ids')"> >> </a><br/>
                                                            <a href="#" onclick="listbox_move('category_selected', 'category_source', 0, 'category_ids')"> << </a><br/>
                                                            <a href="#" onclick="listbox_move_all('category_selected', 'category_source', 0, 'category_ids')"> <<<< </a><br/>
                                                        </td>
                                                        <td>
                                                            <SELECT id="category_selected" style="width: 150px;" size="10" multiple>
																<?php foreach ($project_selected as $v1) : ?>															
                                                                    <option value="<?php echo $v1["id"] ?>">
                                                                        <?php echo $v1["name"] ?>
                                                                    </option>
                                                                <?php endforeach; ?>									

                                                            </SELECT>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>                                   
                                        </div>											
										
										<div class="form-field">
                                            <label class="cm-required">Status:</label>
                                            <div class="select-field">											
												<?php echo Form::radio('sendstate_id', 1, $push->sendstate_id == '1', ['id' => 'active']); ?>
												<label for="active">Sent</label>
												<?php echo Form::radio('sendstate_id', 2, $push->sendstate_id == '2', ['id' => 'inactive']); ?>
												<label for="inactive">Draft</label>                                                
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
                                        <input type="submit" name="dispatch[profiles.update]" value="{{$create}}" onclick="onSubmit()" />
                                    </span>
                                    &nbsp;&nbsp;&nbsp;
                                    <span class="cm-button-main cm-process-items">
                                        <input type="button" onclick="location.href = '/push';"  value="Cancel" />
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

<script>
	
	$("#pushgroup").change(function() {
		showProjectGroup($(this).val());		
	});
	
	showProjectGroup({{$push->pushgroup_id}});
	
	function showProjectGroup(val)
	{
		if (val == "1") {
			$("#project_select").show();			
		}
		else {
			$("#project_select").hide();			
		}
	}
</script>	
@stop

@extends('layout')
@section('content')

<?php
	
	$method = "post";								
	$create = 'Create';
	$title = "Add Unit";
	if( !empty($unit->id) )
	{
		$method = "put";								
		$create = "Save";								
		$title = "Update Unit";
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
							{{ Form::open(array('url' => 'unit/'. $unit->id, 'method' => $method )) }}
                                <div id="content_general">
                                    <fieldset>
                                        <h2 class="subheader">
                                            Unit information
                                        </h2>
																				
										<div class="form-field">
                                            <label for="desc" class="cm-required">Project:</label>
											<?php echo Form::select('project_id', $projects, $unit->project_id); ?>
                                        </div>

                                        <div class="form-field">
                                            <label for="title" class="cm-required">Floor:</label>											
											<input type="number" id="floor" name="floor" class="input-text" size="32" maxlength="5" min="1" max="1000" value="{{$unit->floor}}" />																		
                                        </div>
                                        <div class="form-field">
                                            <label for="title" class="cm-required">Room:</label>
											<input type="number" id="room" name="room" class="input-text" size="32" maxlength="5" min="1" max="1000" value="{{$unit->room}}" />                                           
                                        </div>
                                        
										<div class="form-field">
                                            <label for="desc" class="cm-required">Unit Info:</label>
                                            <textarea name="info" id="info" cols="55" rows="4" class="input-textarea-long">{{$unit->info}}</textarea>
                                            &nbsp;<div><font color="blue">( Best max: 500 characters )</font></div>
                                        </div>
										
										<input type="hidden" id="floorplan" name="floorplan" value="{{$unit->floorplan}}" />										
										<div class="form-field" id="floorplan">
                                            <label>Floor Plan:</label>
                                            <table border="0">
                                                <tr>
                                                    <td width="400px">
                                                        <div id="floorplan_upload">Upload</div>
                                                    </td>
                                                    <td valign="middle">
                                                        <div><font color="blue">Max size: 10MB (900 * 400 pixels) , *.jpg, *.png</font></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="400px">
                                                        <div id="floorplan_status"><img src="{{"/uploads/file/".$unit->floorplan}}" width="100px" height=100px></div>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>
										

										<div class="form-field">
                                            <label for="desc" class="cm-required">Reserve State:</label>
											<?php echo Form::select('reservestate_id', $state, $unit->reservestate_id); ?>
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
                                        <input type="button" onclick="location.href = '/unit';"  value="Cancel" />
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

<script type="text/javascript">
	var floorplan = {
        url: "/upload",
        dragDrop: false,
        fileName: "myfile",
        multiple: false,
        showCancel: false,
        showAbort: false,
        showDone: false,
        showDelete: false,
        showError: true,
        showStatusAfterSuccess: false,
        showStatusAfterError: false,
        showFileCounter: false,
        allowedTypes: "jpg,png",
        maxFileSize: 5120000,
        returnType: "text",
        onSuccess: function(files, data, xhr)
        {
            $("#floorplan").val(data);
 //           $("#status").html("<div>" + data + "</div>");
            $("#floorplan_status").html("<img width=100px height=100px src=\"/uploads/file/" + data + "\">");			
        },
        deleteCallback: function(data, pd)
        {
            for (var i = 0; i < data.length; i++)
            {
                $.post("file_delete.php", {op: "delete", name: data[i]},
                function(resp, textStatus, jqXHR)
                {
                    //Show Message
                    $("#floorplan_status").html("<div>File Deleted</div>");
                });
            }
            pd.statusbar.hide(); //You choice to hide/not.

        }
    }
	
	$("#floorplan_upload").uploadFile(floorplan);
</script>
@stop

@extends('layout')
@section('content')

<?php
	
	$method = "post";								
	$create = 'Create';
	$title = "Add News";
	if( !empty($news->id) )
	{
		$method = "put";								
		$create = "Save";								
		$title = "Update News";
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
							{{ Form::open(array('url' => 'news/'. $news->id, 'method' => $method )) }}
                                <div id="content_general">
                                    <fieldset>
                                        <h2 class="subheader">
                                            News information
                                        </h2>

                                        <div class="form-field">
                                            <label for="title" class="cm-required">Title:</label>
                                            <input type="text" id="title" name="title" class="input-text" size="32" maxlength="50" value="{{$news->title}}" />											
                                        </div>
                                        
										<div class="form-field">
                                            <label for="desc" class="cm-required">Description:</label>
                                            <textarea name="desc" id="desc" cols="55" rows="4" class="input-textarea-long">{{$news->desc}}</textarea>
                                            &nbsp;<div><font color="blue">( Best max: 500 characters )</font></div>

                                        </div>
										
										<div class="form-field">
                                            <label for="file_type">File Type:</label>
											<?php echo Form::select('filetype_id', $filetype, $news->filetype_id, array('id' => 'filetype')); ?>
                                        </div>

										<div id="youtube" class="form-field">
                                            <label for="url" class="cm-required">Youtube:</label>
                                            <input type="text" name="url" class="input-text" size="50" id="file_url"  value="{{$news->url}}"/>
                                        </div>  
																		
										
										<div class="form-field" id="file">
                                            <label>File:</label>
                                            <table border="0">
                                                <tr>
                                                    <td width="400px">
                                                        <div id="file_upload">Upload</div>
                                                    </td>
                                                    <td valign="middle">
                                                        <div id="filehint"><font color="blue">Max size: 10MB (900 * 400 pixels) , *.jpg, *.png</font></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="400px">
                                                        <div id="file_status">{{$news->url}}</div>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>
										
										
										
										<input type="hidden" id="thumb_url" name="thumbnail" value="{{$news->thumbnail}}" />										
										<div class="form-field" id="thumbnail">
                                            <label>Thumbnail:</label>
                                            <table border="0">
                                                <tr>
                                                    <td width="400px">
                                                        <div id="thumnail_upload">Upload</div>
                                                    </td>
                                                    <td valign="middle">
                                                        <div><font color="blue">Max size: 10MB (900 * 400 pixels) , *.jpg, *.png</font></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="400px">
                                                        <div id="status"><img width=100px height=100px src="/uploads/file/{{$news->thumbnail}}">"</div>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>

										

										<div class="form-field">
                                            <label for="desc" class="cm-required">Project:</label>
											<?php echo Form::select('project_id', $projects, $news->project_id); ?>
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
                                        <input type="button" onclick="location.href = '/news';"  value="Cancel" />
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
	var thumbnail = {
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
            $("#thumb_url").val(data);
 //           $("#status").html("<div>" + data + "</div>");
            $("#status").html("<img width=100px height=100px src=\"/uploads/file/" + data + "\">");			
        },
        deleteCallback: function(data, pd)
        {
            for (var i = 0; i < data.length; i++)
            {
                $.post("file_delete.php", {op: "delete", name: data[i]},
                function(resp, textStatus, jqXHR)
                {
                    //Show Message
                    $("#status").html("<div>File Deleted</div>");
                });
            }
            pd.statusbar.hide(); //You choice to hide/not.

        }
    }
	
	$("#thumnail_upload").uploadFile(thumbnail);
	
	
	var file = {
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
        maxFileSize: 5120000,
        returnType: "text",
        onSuccess: function(files, data, xhr)
        {
            $("#file_url").val(data);			
 //           $("#status").html("<div>" + data + "</div>");
            $("#file_status").html(data);			
        },
        deleteCallback: function(data, pd)
        {
            for (var i = 0; i < data.length; i++)
            {
                $.post("file_delete.php", {op: "delete", name: data[i]},
                function(resp, textStatus, jqXHR)
                {
                    //Show Message
                    $("#status").html("<div>File Deleted</div>");
                });
            }
            pd.statusbar.hide(); //You choice to hide/not.

        }
    }
	
	$("#file_upload").uploadFile(file);
	
</script>

<script type='text/javascript' src='/js/nicEdit.js'></script>
<script>
	
	bkLib.onDomLoaded(function() {
		new nicEditor({fullPanel : true}).panelInstance('desc');
	});
	
	function onSubmit(){
		var nicE = new nicEditors.findEditor('desc');
		var question = nicE.getContent();
		$("#desc").val(question );
		//alert(question );
		document.forms[''].submit();
	}
	
	showFileTypeControls({{$news->filetype_id}});
	$("#file_url").val("{{$news->url}}");		
	$("#file_status").html("{{$news->url}}");		
	
	$("#filetype").change(function() {		
		showFileTypeControls($(this).val());		
	});
	
	function showFileTypeControls(val)
	{
		if( val == 1 ) // Image
		{
			$("#thumbnail").show();			
			$("#youtube").hide();
			$("#file").hide();
			
			$("#filehint").html("<font color=\"blue\">Max size: 10MB (900 * 400 pixels) , *.jpg, *.png</font>");
		}
		
		if( val == 2 ) // pdf
		{
			$("#thumbnail").show();			
			$("#file").show();
			$("#youtube").hide();			
			
			$("#filehint").html("<font color=\"blue\">Max size: 10MB *.pdf</font>");
		}		
		
		if( val == 3 ) // video
		{
			$("#thumbnail").show();			
			$("#file").show();
			$("#youtube").hide();			
			
			$("#filehint").html("<font color=\"blue\">Max size: 10MB *.avi, *.mp4</font>");
		}
		
		if( val == 4 ) // youtube
		{
			$("#thumbnail").hide();			
			$("#file").hide();
			$("#youtube").show();			
		}
		
		$("#file_url").val('');		
		$("#file_status").html('');		
	}
	
	
	
</script>

@stop

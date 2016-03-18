@extends('layout')
@section('content')

<?php
	
	$method = "post";								
	$create = 'Create';
	$title = "Add IJM Land";
	if( !empty($property->id) )
	{
		$method = "put";								
		$create = "Save";								
		$title = "Update IJM Land";
	}
	
	$video = "";
	$vids = explode(", ", $property->video);
	for($i = 0; $i < sizeof($vids) ; $i++){
		
		if($vids[$i] != ""){
			if (strpos($vids[$i],"youtube") !== false) {
				$stop   = mb_strlen($vids[$i]); 
				$vidx = mb_substr( $vids[$i], $stop-5, $stop); 
				$video .= "<div id='".$vidx."' onclick=deleteVideoItem('".$vidx."')>".$vids[$i]."</div>";
			}else{
				$video .= "<div id='".$vids[$i]."' onclick=deleteVideoItem('".$vids[$i]."')>".$vids[$i]."</div>";
			}
		}
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
							{{ Form::open(array('url' => 'property/'. $property->id, 'method' => $method, 'id' => 'form' )) }}
                                <div id="content_general">
                                    <fieldset>
                                        <h2 class="subheader">
                                            IJM Land information
                                        </h2>
										
										<h2 class="subheader1">
                                            &nbsp;&nbsp;&nbsp;Information
                                        </h2>
																				
										<div class="form-field">
                                            <label for="title" class="cm-required">Title:</label>
											<input type="text" id="title" name="title" class="input-text" size="32" maxlength="50" value="{{$property->title}}" />
                                        </div>

										<div class="form-field">
                                            <label for="desc" class="cm-required">Description:</label>
                                            <textarea name="desc" id="desc" cols="55" rows="4" class="input-textarea-long">{{$property->desc}}</textarea>
                                            &nbsp;<div><font color="blue">( Best max: 500 characters )</font></div>
                                        </div>
										<input type="hidden" id="thumbnail" name="thumbnail" value="{{$property->thumbnail}}" />										
										<div class="form-field" id="thumbnail">
                                            <label>Thumbnail:</label>
                                            <table border="0">
                                                <tr>
                                                    <td width="400px">
                                                        <div id="thumbnail_upload">Upload</div>
                                                    </td>
                                                    <td valign="middle">
                                                        <div><font color="blue">Max size: 10MB (900 * 400 pixels) , *.jpg, *.png</font></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="400px">
                                                        <div id="thumbnail_status"><img src="{{"/uploads/file/".$property->thumbnail}}" width="100px" height=100px></div>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>				

										<h2 class="subheader1">
                                            &nbsp;&nbsp;&nbsp;Gallery
                                        </h2>   
										
										<input type="hidden" id="gallery" name="gallery" value="{{$property->gallery}}" />										
										<div class="form-field" id="thumbnail">
                                            <label>Images:</label>
                                            <table border="0">
                                                <tr>
                                                    <td width="70px">
                                                        <div id="gallery_upload">Upload</div>
                                                    </td>
                                                    <td width="200px">
														<span>Sequence:</span>
														<input type="number" id="gallery_sequence" name="sequence" class="input-text" size="32" maxlength="5" min="1" max="1000" value="{{$property->sequence}}" />											                                                        
                                                    </td>
                                                    <td valign="middle">
                                                        <div><font color="blue">Max size: 2MB (400 * 400 pixels) , *.jpg, *.png</font></div>
                                                    </td>
                                                </tr>
											</table>
                                            <table border="0">
                                                <tr>
                                                    <td width="100%">
                                                        <div id="gallery_status"></div>
                                                    </td>
                                                </tr>
                                            </table>													
                                        </div>				
										
										<h2 class="subheader1">
                                            &nbsp;&nbsp;&nbsp;Video
                                        </h2>   
										
										<input type="hidden" id="video" name="video" value="{{$property->video}}" />	
										<div class="form-field">
                                            <label for="file_type">File Type:</label>
                                            <select name="file_type" id="file_type">
                                                <option value="video" selected="selected" >Video</option>
                                                <option value="youtube" >youtube</option>
                                            </select>
                                        </div>

                                        <div class="form-field" id="div_youtube" hidden="">
                                            <label for="youtube_url">YouTube URL:</label>
                                            <input type="text" id="youtube_url" name="youtube_url" class="input-text" size="80" maxlength="200" value="" onkeydown="addVideoItem(event)"/>
                                        </div>
										<div class="form-field" id="div_video">
                                            <label>Video:</label>
                                            <table border="0">
                                                <tr>
                                                    <td width="400px">
                                                        <div id="video_upload">Upload</div>
                                                    </td>
                                                    <td valign="middle">
                                                        <div><font color="blue">Max size: 30MB, *.mp4, *.avi, *.mov</font></div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
										
										<div class="form-field" id="div_pdf">
                                            <table border="0">
                                                <tr>
                                                    <td width="400px">
                                                        <div id="video_status">{{$video}}</div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
										
										<h2 class="subheader1">
                                            &nbsp;&nbsp;&nbsp;Contact
                                        </h2>      
                                        <div class="form-field">
                                            <label for="contact_title" class="cm-required">Title:</label>
                                            <input type="text" name="contact_title" class="input-text" size="50"  value="{{$property->contact_title}}"/>
                                        </div>     
										
										<div class="form-field">
                                            <label for="addr" class="cm-required">Address:</label>
                                            <input type="text" name="addr" class="input-text" size="50"  value="{{$property->addr}}"/>

                                        </div>  
                                        <div class="form-field">
                                            <label for="lat" class="cm-required">Latitude:</label>
                                            <input type="text" name="lat" class="input-text" size="10"  value="{{$property->lat}}"/>

                                        </div>
                                        <div class="form-field">
                                            <label for="lon" class="cm-required">Longitude:</label>
                                            <input type="text" name="lon" class="input-text" size="10"  value="{{$property->lon}}"/>

                                        </div>  

										<div class="form-field">
                                            <label for="website" class="cm-required">Website:</label>
                                            <input type="text" name="website" class="input-text" size="50"  value="{{$property->contact_website}}"/>

                                        </div>     										
										
                                        <div class="form-field">
                                            <label for="contact_email" class="cm-required">Email:</label>
                                            <input type="text" name="contact_email" class="input-text" size="20"  value="{{$property->contact_email}}"/>

                                        </div>
                                        <div class="form-field">
                                            <label for="contact_no" class="cm-required">Tel:</label>
                                            <input type="text" name="contact_no" class="input-text" size="20"  value="{{$property->contact_no}}"/>

                                        </div>   
                                        <div class="form-field">
                                            <label for="contact_desc" class="cm-required">Fax:</label>
                                            <input type="text" name="contact_desc" class="input-text" size="20"  value="{{$property->contact_desc}}"/>

                                        </div>   
										
										<input type="hidden" id="location_url" name="location_url" value="{{$property->location_url}}" />
										<div class="form-field">
											<label for="contact_location" class="cm-required">Location:</label>
                                            <table border="0">
                                                <tr>
                                                    <td width="400px">
                                                        <div id="location_upload">Upload</div>
                                                    </td>
                                                    <td valign="middle">
                                                        <div><font color="blue">Max size: 10MB (900 * 400 pixels) , *.jpg, *.png</font></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="400px">
                                                        <div id="location_status"><img src="{{"/uploads/file/".$property->location_url}}" width="100px" height=100px></div>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>
										
                                        <div class="form-field">
                                            <label class="cm-required">Status:</label>
                                            <div class="select-field">											
												<?php echo Form::radio('state_id', 1, $property->state_id == '1', ['id' => 'active']); ?>
												<label for="active">Active</label>
												<?php echo Form::radio('state_id', 0, $property->state_id == '0', ['id' => 'inactive']); ?>
												<label for="inactive">Inactive</label>                                                
                                            </div>											
                                        </div>
										
										<h2 class="subheader1">
                                            &nbsp;&nbsp;&nbsp;Register
                                        </h2>      
                                        <div class="form-field">
                                            <label for="url" class="cm-required">URL:</label>
                                            <input type="text" name="url" class="input-text" size="50"  value="{{$property->url}}"/>

                                        </div>   
										
										
                                    </fieldset>

                                </div>

<!--                                <p class="select-field notify-customer cm-toggle-button">
    <input type="checkbox" name="notify_customer" value="Y" checked="checked" class="checkbox" id="notify_customer" />
    <label for="notify_customer">Notify user</label>
</p>-->
							
                                <div class="buttons-container buttons-bg cm-toggle-button">
                                    <span  class="submit-button cm-button-main ">
                                        <input type="submit" name="" value="{{$create}}" onclick="onSubmit()" />
                                    </span>
                                    &nbsp;&nbsp;&nbsp;
                                    <span class="cm-button-main cm-process-items">
                                        <input type="button" onclick="location.href = '/property';"  value="Cancel" />
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
	var images = "{{$property->gallery}}";
	var img_array = images.split(", ");
	if( images == "" )
		img_array = [];
	
	displayGallery(img_array);
		
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
            $("#thumbnail").val(data);
 //           $("#status").html("<div>" + data + "</div>");
            $("#thumbnail_status").html("<img width=100px height=100px src=\"/uploads/file/" + data + "\">");			
        },
        deleteCallback: function(data, pd)
        {
            for (var i = 0; i < data.length; i++)
            {
                $.post("file_delete.php", {op: "delete", name: data[i]},
                function(resp, textStatus, jqXHR)
                {
                    //Show Message
                    $("#thumbnail_status").html("<div>File Deleted</div>");
                });
            }
            pd.statusbar.hide(); //You choice to hide/not.

        }
    }
	
	$("#thumbnail_upload").uploadFile(thumbnail);
	
	var gallery = {
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
			var num = $("#gallery_sequence").val();
			if( num == "" )
				img_array.push(data);
			else
				img_array.splice((num - 1), 0, data);
			
			displayGallery(img_array);			
        },
        deleteCallback: function(data, pd)
        {
            for (var i = 0; i < data.length; i++)
            {
                $.post("file_delete.php", {op: "delete", name: data[i]},
                function(resp, textStatus, jqXHR)
                {
                    //Show Message
                    $("#gallery_status").html("<div>File Deleted</div>");
                });
            }
            pd.statusbar.hide(); //You choice to hide/not.

        }
    }
	
	$("#gallery_upload").uploadFile(gallery);
	
	function displayGallery(array)
	{
		images = "";
		var html = "";
		for (var i = 0; i < array.length; i++)
		{
			html += "<div style='float:left'><img id='"+array[i]+"' src='/uploads/file/" + array[i] + "' width='200' height='200' onclick=deleteGallery('"+array[i]+"')>"+
															"<p align='center'>Sequence: "+(i+1)+"</p></div>";
			if( i == 0 )
				images += array[i];
			else
				images += ", " + array[i];
		}
		document.getElementById("gallery_status").innerHTML = html;
		$("#gallery").val(images);
	}
	
	function deleteGallery(itempath){
		if(confirm("Do you want to remove this image?")){
			images = "";
			var html = "";
			var new_array = [];
			var count = 0;
			for (var i = 0; i < img_array.length; i++)
			{
				if( img_array[i] == itempath )
					continue;
				
				new_array.push(img_array[i]);
				html += "<div style='float:left'><img id='"+img_array[i]+"' src='/uploads/file/" + img_array[i] + "' width='200' height='200' onclick=deleteGallery('"+img_array[i]+"')>"+
																"<p align='center'>Sequence: "+(i+1)+"</p></div>";
				if( count == 0 )
					images += img_array[i];
				else
					images += ", " + img_array[i];
				count++;
			}
			document.getElementById("gallery_status").innerHTML = html;
			$("#gallery").val(images);
			img_array = new_array;
		}
	}
	
</script>

<link rel='stylesheet' href='/css/jquery.datetimepicker.css'/>
<script src='/js/jquery.datetimepicker.full.js'></script>
<script>
	$('#startdate').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		formatDate:'Y/m/d'
	});
	$('#enddate').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		formatDate:'Y/m/d'
	});

</script>

<script>

	var videos = "{{$property->video}}";

	$("#file_type").change(function() {
		if ($(this).val() == "video") {
			$("#div_youtube").hide();
			$("#div_video").show();
		}
		else {
			$("#div_youtube").show();
			$("#div_video").hide();
		}
	});
	
	var video = {
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
        allowedTypes: "mp4,avi,mov,wmv",
        maxFileSize: 31200000,
        returnType: "text",
        onSuccess: function(files, data, xhr)
        {
			$("#video").val(videos);
			//$("#status1").html("<div id="+data+" onclick=deleteItem('"+data+"')>" + data + "</div>"););
			document.getElementById("video_status").innerHTML += "<div id='"+data+"' onclick=deleteVideoItem('"+data+"')>" + data + "</div>";//"<source id='"+data+"' src='../uploads/file/" + data + "' width='200' height='200' onclick=deleteVideoItem('"+data+"')>";
        },
        deleteCallback: function(data, pd)
        {
            for (var i = 0; i < data.length; i++)
            {
                $.post("file_delete.php", {op: "delete", name: data[i]},
                function(resp, textStatus, jqXHR)
                {
                    //Show Message
                    $("#video_status").html("<div>File Deleted</div>");
                });
            }
            pd.statusbar.hide(); //You choice to hide/not.

        }
    }
	
	$("#video_upload").uploadFile(video);
	
	function addVideoItem(event){
		if (event.keyCode == 13) { 
			var data = document.getElementById("youtube_url").value;
			if(data != ""){
				var did = data.substring(data.length-4, data.length);
				
				
				var total = document.getElementById("video").value + ", " + data;
				$("#video").val(total);
				document.getElementById("video_status").innerHTML += "<div id='"+did+"' onclick=deleteVideoItem('"+did+"')>" + data + "</div>";
				document.getElementById("youtube_url").value = "";
			}
		}
	}	
	
	function deleteVideoItem(itempath){
		if(confirm("Do you want to remove this video?")){
			var total = document.getElementById("video").value;
			var path = itempath;
			var strArray = new Array();
			strArray = total.split(", ");  	
			var i= 0;
			for(i = 0; i < strArray.length; i++){
				var idx = 0;
				
				if((idx = strArray[i].indexOf(itempath)) != -1){
					path = strArray[i];
					break;
				}
			}
			
					
			total = total.replace(path, ''); 
			
			total = total.replace(', ,', ', ');
			if(total.substring(0,1) == ','){
				total = total.substring(2,total.length);
			}
			if(total.substring(total.length-2, total.length-1) == ','){
				total = total.substring(0, total.length-2);
			}
			document.getElementById(itempath).style.display = "none";
			$("#video").val(total);
		}
	}

	var position = {
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
            $("#location").val(data);
 //           $("#status").html("<div>" + data + "</div>");
            $("#location_status").html("<img width=100px height=100px src=\"/uploads/file/" + data + "\">");			
        }
    }
	
	$("#location_upload").uploadFile(position);
	
	//$("#location_upload").uploadFile(location);
	
</script>

@stop

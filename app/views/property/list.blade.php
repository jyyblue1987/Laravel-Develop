@extends('layout')
@section('content')

<?php
	$current_url = Request::url();
?>

<div id="main_column" class="clear">
   <div>                
		<div class="clear mainbox-title-container">
		        <div class="tools-container">
					<span class="action-add">
						<a href="/property/create">Add IJM Land</a>
					</span>
                </div>
				<h1 class="mainbox-title float-left">
					IJM Land	
				</h1>
		</div>
		
		<div id="search-form" class="section-border">
			<form name="user_search_form" method="GET" class="">
				<table cellpadding="0" cellspacing="0" border="0" class="search-header">
					<tr>
						<td class="search-field nowrap">
							<label for="elm_name"></label>
							<div class="break">
								<input class="input-text" type="text" size="15" name="search" id="search" value="{{Input::old('search')}}"/>
							</div>
						</td>						
						<td class="buttons-container">
							<span  class="submit-button ">
								<input type="submit" value="Search" />
							</span>
						</td>
						<input type="hidden" id="pagesize" name="pagesize" value="{{$pagesize}}" />
					</tr>
				</table>
			</form>
		</div>

		<div class="mainbox-body" >
			<div>
				<form action="{{$current_url}}" method="GET">
					<div id="data_grid_view" class="grid-view">
					<div class="summary">
					<?php 
						$start = ($property->getCurrentPage() - 1) * $property->getPerPage() + 1; 
						$end = $start + $property->count() - 1 
					?>
					{{'Displaying ' .  $start . '-' . $end . ' of ' . $property->getTotal() . ' results.'}}
					</div>
					<table class="items">
						<thead>
							<tr>
							    <th width="1%" class="center cm-no-hide-input">
									<input type="checkbox" name="check_all" value="Y" title="Check / uncheck all" class="checkbox cm-check-items" />
                                </th>
								<th width="3%"><a>No</a></th>
								<th width="30%">{{SortableTrait::link_to_sorting_action("title", "Title")}}</th>							
								<th width="15%">Thumbnail</th>								
								<th width="10%">{{SortableTrait::link_to_sorting_action("reportdate", "Report Date")}}</th>
								<th width="10%">{{SortableTrait::link_to_sorting_action("updated_at", "Last Modified")}}</th>								
								<th width="10%">{{SortableTrait::link_to_sorting_action("state_id", "Status")}}</th>
								<th class="button-column" id="data_grid_view_c8">
									<?php echo Form::select('pagesize', array('10' => '10', '20' => '20', '50' => '50', '100' => '100'), $pagesize, array('onchange' => 'this.form.submit()')); ?>									
								</th>
							</tr>
						</thead>	
						<tbody>
							<?php $k = 1; ?>	
							@foreach ( $property as $value )	
								
								<tr class="odd" onclick="showDetail({{$value['id']}})">
									<td class="center cm-no-hide-input">
										<input type="checkbox" name="ids[]" value={{$value['id']}}" class="checkbox cm-item" />
									</td>
									<?php $no = $k + $start - 1; ?>
									<td> {{$no}}</td>
									<td >
										<a href="{{$current_url}}/{{$value['id']}}/edit"><span>{{$value['title']}}</span></a>
									</td>									
									<td>										
										<a href="/uploads/file/{{$value['thumbnail']}}" target="_blank">{{$value['thumbnail']}}</a>			
									</td>
									<td>
										{{date("Y-m-d", strtotime($value['reportdate']))}}
									</td>		
									<td>
										{{$value['updated_at']}}
									</td>												
									<td>
										{{$value->state['name']}}
									</td>
									<td class="nowrap">
										<a class="tool-link " href="{{$current_url}}/{{$value['id']}}/edit" >Edit</a>
										&nbsp;&nbsp;|
										<ul class="cm-tools-list tools-list">
											<li><a class="cm-confirm" href="{{$current_url}}/delete/{{$value['id']}}">Delete</a></li>
										</ul>

									</td>
								</tr>
								<tr id="{{$value['id']}}" style="display:none">
									<td colspan="7">
									<table>
										<tr>
											<td>
												<h2 class="subheader1">
													&nbsp;&nbsp;&nbsp;Gallery
												</h2>
												<div>
													<?php
														$imgs = explode(", ", $value['gallery']);
														
														for($i = 0; $i < sizeof($imgs) ; $i++){
															
															if($imgs[$i] != ""){
																 echo "<img id='".$imgs[$i]."' src = '/uploads/file/".$imgs[$i]."' width='200' height='200'>";
															}
														}
													?>
												</div>
												<h2 class="subheader1">
													&nbsp;&nbsp;&nbsp;Video
												</h2>
												
												<?php							
																										
													$vids = explode(", ", $value->video);													
													for($i = 0; $i < sizeof($vids) ; $i++){
														
														if($vids[$i] != ""){
															if (strpos($vids[$i],"youtube") !== false) {
																$stop   = mb_strlen($vids[$i]); 
																$vidx = mb_substr( $vids[$i], $stop-5, $stop);																
																echo "<div><a href='".$vids[$i]."' target='_blank'>".$vids[$i]."</a></div>";
															}else{
																echo "<div><a href='/uploads/file/".$vids[$i]."' target='_blank'>".$vids[$i]."</a></div>";														
															}
														}
													}													
												?>
												
												
												<h2 class="subheader1">
													&nbsp;&nbsp;&nbsp;Contact
												</h2>
												<div>													
												  Title: <?php echo $value['contact_title']; ?>
												</div>
												<div>													
												  Address:  <a href="https://www.google.com.my/maps/place/<?php echo $value['addr']; ?>" target="_blank"><?php echo $value['addr']; ?></a>
												</div>
												<div>													
												  Position: <a href="https://www.google.com.my/maps/place/<?php echo $value['lat']; ?>, <?php echo $value['lon']; ?>" target="_blank"><?php echo $value['lat']; ?>, <?php echo $value['lon']; ?></a>
												</div>
												<div>													
												  Email: <?php echo $value['contact_email']; ?>
												</div>
												<div>													
												  Tel: <?php echo $value['contact_no']; ?>
												</div>
												<div>													
												  Fax: <?php echo $value['contact_desc']; ?>
												</div>
												<div>													
												  Website: <?php echo $value['contact_website']; ?>
												</div>
												<h2 class="subheader1">
													&nbsp;&nbsp;&nbsp;Register
												</h2>
												<div>													
												  URL: <?php echo $value['url']; ?>
												</div>
											</td>
										</tr>
									</table>
									</td>
								</tr>								
								
								<?php $k++; ?>
							@endforeach
							
						    @if ($property->getTotal() === 0)
								<tr class="no-items">
									<td class="center cm-no-hide-input" colspan="6">
										<p>No IJM Land List.</p>
									</td>
								</tr>
						    @endif

						</tbody>
					</table>
					<div class="pager">
						Go to page
						<ul class="yiiPager">
							<?php echo with(new ZurbPresenter($property->appends(Input::except('page'))))->render(); ?>																	
						</ul>
					</div>
				   <div class="buttons-container buttons-bg">
						<div class="float-left">
							<span class="submit-button cm-button-main cm-confirm cm-process-items">
								<input class="cm-confirm cm-process-items" type="submit" value="Delete selected" />
							</span>
							<!--&nbsp;&nbsp;&nbsp;
							<span class="cm-button-main cm-process-items">
								<input type="button" onclick="window.open('<?php echo ""; ?>');"  value="Export CSV" />
							</span>-->

							<!--                                        or
																	<ul class="cm-tools-list tools-list">
																		<li><a class="cm-process-items" name="dispatch[profiles.export_range]" rev="userlist_form">Export selected</a></li>
																	</ul>-->

						</div>
					</div>
				</form>
			</div>
		</div>
		
<script>
function showDetail(item){
	if(document.getElementById(item).style.display == "none"){
		document.getElementById(item).style.display = "";
	}else{
		document.getElementById(item).style.display = "none";
	}
}
</script>
@stop

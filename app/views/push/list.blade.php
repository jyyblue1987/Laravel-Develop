@extends('layout')
@section('content')

<?php
	$current_url = Request::url();
?>

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
   <div>                
		<div class="clear mainbox-title-container">
		        <div class="tools-container">
					<span class="action-add">
						<a href="/push/create">Add Push</a>
					</span>
                </div>
				<h1 class="mainbox-title float-left">
					Push Notification	
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
						<td class="search-field">							
							<label for="project_id"></label>
							<?php echo Form::select('project_id', $projects, Input::old("project_id")); ?>
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
						$start = ($push->getCurrentPage() - 1) * $push->getPerPage() + 1; 
						$end = $start + $push->count() - 1 
					?>
					{{'Displaying ' .  $start . '-' . $end . ' of ' . $push->getTotal() . ' results.'}}
					</div>
					<table class="items">
						<thead>
							<tr>
							    <th width="1%" class="center cm-no-hide-input">
									<input type="checkbox" name="check_all" value="Y" title="Check / uncheck all" class="checkbox cm-check-items" />
                                </th>
								<th width="3%"><a>No</a></th>
								<th width="45%">{{SortableTrait::link_to_sorting_action("message", "Broadcast Message")}}</th>
								<th width="10%">{{SortableTrait::link_to_sorting_action("pushgroup_id", "Group")}}</th>
								<th width="15%">{{SortableTrait::link_to_sorting_action("updated_at", "Last Modified")}}</th>								
								<th width="10%">{{SortableTrait::link_to_sorting_action("sendstate_id", "Status")}}</th>								
								<th class="button-column" id="data_grid_view_c8">
									<?php echo Form::select('pagesize', array('10' => '10', '20' => '20', '50' => '50', '100' => '100'), $pagesize, array('onchange' => 'this.form.submit()')); ?>									
								</th>
							</tr>
						</thead>	
						<tbody>
							<?php $i = 1; ?>	
							@foreach ( $push as $value )	
								
								<tr class="odd">
									<td class="center cm-no-hide-input">
										<input type="checkbox" name="ids[]" value={{$value['id']}}" class="checkbox cm-item" />
									</td>
									<?php $no = $i + $start - 1; ?>
									<td> {{$no}}</td>
									<td >
										<a href="{{$current_url}}/{{$value['id']}}/edit"><span>{{$value['message']}}</span></a>
									</td>									
									<td >
										{{$value['pushgroup_id']}}
									</td>		
									<td>
										{{$value['updated_at']}}
									</td>		
									<td>
										{{$value->sendstate['name']}}
									</td>																		
									<td class="nowrap">
										<a class="tool-link " href="{{$current_url}}/send/{{$value['id']}}" >Send</a>
										&nbsp;&nbsp;|
										<a class="tool-link " href="{{$current_url}}/{{$value['id']}}/edit" >Edit</a>
										&nbsp;&nbsp;|
										<ul class="cm-tools-list tools-list">
											<li><a class="cm-confirm" href="{{$current_url}}/delete/{{$value['id']}}">Delete</a></li>
										</ul>

									</td>
								</tr>
								<?php $i++; ?>
							@endforeach
							
						    @if ($push->getTotal() === 0)
								<tr class="no-items">
									<td class="center cm-no-hide-input" colspan="6">
										<p>No Push List.</p>
									</td>
								</tr>
						    @endif

						</tbody>
					</table>
					<div class="pager">
						Go to page
						<ul class="yiiPager">
							<?php echo with(new ZurbPresenter($push->appends(Input::except('page'))))->render(); ?>																	
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
@stop

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
						<a href="/subcategory/create">Add Subcategory</a>
					</span>
                </div>
				<h1 class="mainbox-title float-left">
					Subcategory	
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
							<?php echo Form::select('category_id', $category, Input::old("category_id")); ?>
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
						$start = ($subcategory->getCurrentPage() - 1) * $subcategory->getPerPage() + 1; 
						$end = $start + $subcategory->count() - 1 
					?>
					{{'Displaying ' .  $start . '-' . $end . ' of ' . $subcategory->getTotal() . ' results.'}}
					</div>
					<table class="items">
						<thead>
							<tr>
							    <th width="1%" class="center cm-no-hide-input">
									<input type="checkbox" name="check_all" value="Y" title="Check / uncheck all" class="checkbox cm-check-items" />
                                </th>
								<th width="3%"><a>No</a></th>
								<th width="10%">{{SortableTrait::link_to_sorting_action("title", "Title")}}</th>
								<th width="10%">{{SortableTrait::link_to_sorting_action("category_id", "Parent")}}</th>
								<th width="15%">{{SortableTrait::link_to_sorting_action("source", "Source From")}}</th>								
								<th width="10%">{{SortableTrait::link_to_sorting_action("url", "RSS URL")}}</th>
								<th width="10%">Thumbnail</th>
								<th width="7%">{{SortableTrait::link_to_sorting_action("start", "Start Date")}}</th>
								<th width="7%">{{SortableTrait::link_to_sorting_action("end", "End Date")}}</th>		
								<th width="8%">{{SortableTrait::link_to_sorting_action("sequence", "Sequence")}}</th>	
								<th width="10%">{{SortableTrait::link_to_sorting_action("updated_at", "Last Modified")}}</th>
								<th width="10%">{{SortableTrait::link_to_sorting_action("state_id", "Status")}}</th>								
								<th class="button-column" id="data_grid_view_c8">
									<?php echo Form::select('pagesize', array('10' => '10', '20' => '20', '50' => '50', '100' => '100'), $pagesize, array('onchange' => 'this.form.submit()')); ?>									
								</th>
							</tr>
						</thead>	
						<tbody>
							<?php $i = 1; ?>	
							@foreach ( $subcategory as $value )	
								
								<tr class="odd">
									<td class="center cm-no-hide-input">
										<input type="checkbox" name="ids[]" value={{$value['id']}}" class="checkbox cm-item" />
									</td>
									<?php $no = $i + $start - 1; ?>
									<td> {{$no}}</td>
									<td >
										<a href="{{$current_url}}/{{$value['id']}}/edit"><span>{{$value['title']}}</span></a>
									</td>		
									<td >
										{{$value->category['title']}}</span></a>
									</td>			
									<td>										
										<a href="http://{{$value['source']}}" target="_blank">{{$value['source']}}</a>										
									</td>
									<td>										
										<a href="{{$value['url']}}" target="_blank">{{$value['url']}}</a>										
									</td>							
									<td>
										<span>
											<a href="/uploads/file/{{$value['thumbnail']}}" target="_blank">{{$value['thumbnail']}}</a>
										</span>
									</td>
									<td>
										{{date("Y-m-d", strtotime($value['start']))}}
									</td>		
									<td>
										{{date("Y-m-d", strtotime($value['end']))}}										
									</td>	
									<td>
										{{$value['sequence']}}
									</td>
									<td>
										{{$value['created_at']}}
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
								<?php $i++; ?>
							@endforeach
							
						    @if ($subcategory->getTotal() === 0)
								<tr class="no-items">
									<td class="center cm-no-hide-input" colspan="6">
										<p>No Subcategory List.</p>
									</td>
								</tr>
						    @endif

						</tbody>
					</table>
					<div class="pager">
						Go to page
						<ul class="yiiPager">
							<?php echo with(new ZurbPresenter($subcategory->appends(Input::except('page'))))->render(); ?>																	
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

@extends('layouts.admin') 
 
@section('content')
	 
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px;">
			<a href="#add_panel" class="btn btn-primary btn-sm open-area-btn">
				<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Добавить
			</a> 
		</div>

		<div class="col-md-12 area-panel" id="add_panel"> 
			<div class="portlet light bg-inverse"> 

				<div class="portlet-title"> 
		            @include('admin.utils.language_switcher') 
		         </div>
	 
				<div class="portlet-body form">   
	 
					<form action="/{{ $method }}/create" class="ajax__submit form-horizontal">  

						{{ csrf_field() }}

						<div class="form-body" style="padding-top: 20px;"> 
							<div class="tab-content"> 
								@include('admin.utils.input', ['label' => 'Название', 'lang' => true, 'name' => 'name']) 
							</div> 
						</div>
						<div class="form-actions">
							<div class="btn-set pull-left"> 
								<button type="submit" class="btn green">Сохранить</button>
							</div> 
						</div>
					</form> 
				</div>
			</div>
		</div>

	   	<div class="col-md-12">  
	   		@if($data->count())
	   		<form action="/{{ $method }}/save-access" class="ajax__submit no-reset"> 
	   			{{ csrf_field() }}
		      	<table class="table table-bordered">
					<tbody>
					<tr>
						<td style="width:50px; text-align:center;"></td>
						<th style="width:5%; text-align: center"><i class="fa fa-check-square" aria-hidden="true"></i></th>
						<th class="nw">Название</th> 
						@foreach($user_types as $type)
							<th style="width: 50px;">
								{{ $type->name_ru }}
							</th>
						@endforeach
						<th style="width:5%; text-align: center"><i class="fa fa-cogs" aria-hidden="true"></i></th>
					</tr>
					</tbody>
					<tbody id="sort-items" data-table="{{ $table }}" data-action="{{ route('sortElement') }}">
					@foreach($data as $item)
						<tr id="<?=$item['id']?>">
							<td style="width:50px; text-align:center;" class="handle"> </td> 
							<td style="width:5px; white-space: nowrap;">
								<input type="checkbox"
									   class="make-switch" data-size="mini" {{ !empty($item['view']) ? 'checked' : '' }}
									   data-on-text="<i class='fa fa-check'></i>"
									   data-off-text="<i class='fa fa-times'></i>"
									   onchange="Ajax.buttonView(this, '{{ $table }}', '{{ $item["id"] }}', 'view')">
							</td>
							<td class="nw">{{ $item->name_ru }}</td> 
							@php
								$access = $item->access->count() ? $item->access->keyBy('type') : [];
							@endphp
							@foreach($user_types as $type)
								<td>
									<input type="checkbox"
									   class="make-switch" data-size="mini"
									   data-on-text="<i class='fa fa-check'></i>"
									   data-off-text="<i class='fa fa-times'></i>"
									   {{ !empty($access[$type->type]) ? 'checked' : '' }}
									   name="access[{{ $item->id }}][{{ $type->type }}]"
									   onchange="$(this).closest('form').submit();">
								</td>
							@endforeach
							<td style="width: 5px; white-space: nowrap">
								<a style="margin-left: 5px;" href="/{{ $method }}/{{ $item['id'] }}/edit/" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
								<!-- <a class="btn btn-danger btn-xs" data-toggle="modal" href="#deleteModal_{{ $table }}_{{ $item['id'] }}"><i class="fa fa-trash-o "></i></a> -->
								<!-- Modal -->
							@include('admin.utils.delete', ['id' => $item['id'], 'table' => $table])
							<!-- Modal -->
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</form>
			@else
				<div class="alert alert-warning">Нет данных</div>
			@endif
	   	</div>
	</div>
@stop


@extends('layouts.admin') 
 
@section('content')
	 
	<div class="row">  
	   	<div class="col-md-12">  
	   		@if($data->count())
	      	<table class="table table-bordered">
				<tbody>
				<tr> 
					<th class="nw">Имя</th> 
					<th class="nw">Телефон</th> 
					<th class="nw">Сообщение</th> 
					<th class="nw">Официант</th> 
					<th class="nw">Число</th> 
					<th style="width:5%; text-align: center"><i class="fa fa-cogs" aria-hidden="true"></i></th>
				</tr>
				</tbody>
				<tbody>
				@foreach($data as $item)
					<tr> 
						<td class="nw">
							{{ $item->name }}
						</td> 
						<td class="nw">
							{{ $item->phone }}
						</td> 
						<td>
							{{ $item->message }}
						</td> 
						<td class="nw">
							@if($item->id_user)
								{{ $item->user->name }} {{ $item->user->lastname }}
							@else
								--
							@endif
						</td> 
						<td>{{ $item->created_at->format('d.m.Y H:i') }}</td>
						<td style="width: 5px; white-space: nowrap"> 
							<a class="btn btn-danger btn-xs" data-toggle="modal" href="#deleteModal_{{ $table }}_{{ $item['id'] }}"><i class="fa fa-trash-o "></i></a>
							<!-- Modal -->
						@include('admin.utils.delete', ['id' => $item['id'], 'table' => $table])
						<!-- Modal -->
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			@else
				<div class="alert alert-warning">Нет сообщение</div>
			@endif
	   	</div>
	</div> 
@stop


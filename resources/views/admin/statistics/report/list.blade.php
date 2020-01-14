@extends('layouts.admin') 
 
@section('content')
	
	<form action="" style="margin-bottom: 30px;">
		<div class="row"> 
			<input type="hidden" name="filter" value="1"> 

			<div class="col-md-3">
				<div class="input-group input-large date-picker input-daterange" data-date-format="dd.mm.yyyy">
					<input type="text" class="form-control" autocomplete="off" value="{{ request()->from }}" name="from">
					<span class="input-group-addon">
					по </span>
					<input type="text" class="form-control" autocomplete="off" value="{{ request()->to }}" name="to">
				</div>
			</div>

			<div class="col-md-3">
				<button type="submit" class="btn btn-info">Поиск</button>
				@if(request()->filter)
					<a href="{{ route('admin_withdrawal') }}" class="btn btn-danger">Сбросить</a>
				@endif
			</div>  
		</div> 
	</form>

	<div class="row">  
	   	<div class="col-md-12">  
	   		@if($data->count())
	   			<a href="/{{ $method }}/export?from={{ request()->from }}&to={{ request()->to }}" class="btn btn-sm btn-info">Экспорт</a>
	   			<table class="table table-bordered eq-table-cell" style="margin-bottom: 30px; margin-top: 15px;">
	   				<thead>
	   					<tr>
	   						<th class="ac">Всего руб.</th>
							<th class="ac" align="center">Чаевые руб.</th> 
							@foreach($percents as $percent)
								<th class="nw ac">{{ $percent->name }}</th>  
							@endforeach
	   					</tr>
	   				</thead>
	   				<tbody>
	   					<tr>
	   						<td class="ac">{{ $data->sum('total_amount') }}</td>
	   						<td class="ac">{{ $data->sum('amount') }}</td>
	   						@foreach($percents as $percent) 
								<td class="ac percent-total-{{ $percent->id }}"></td>  
							@endforeach
	   					</tr>
	   				</tbody>
	   			</table>
		      	<table class="table table-bordered">
					<thead>
						<tr> 
							<th>Дата</th>
							<th class="nw">№ Перевода</th>  
							<th class="nw">Официант</th> 
							<th class="nw">Партнер</th> 
							<th class="ac">Всего руб.</th>
							<th class="ac">Чаевые руб.</th> 
							@foreach($percents as $percent)
								<th class="nw ac">{{ $percent->name }}</th>  
							@endforeach
						</tr>
					</thead>
					<tbody>
						@foreach($data as $item)
							@php
								$tipPercents = $item->percents->keyBy('id_percent');
							@endphp
							<tr> 
								<td class="nw">
									{{ $item->created_at->format('d.m.Y H:i:s') }}
								</td>
								<td class="nw">
									{{ $item->id_transaction }}
								</td>  
								<td>
									@if(!$item->id_location)
										{{ $item->user->name }} {{ $item->user->lastname }} ({{ $item->user->rand }})
									@else
										{{ $item->location->institution_name }} ({{ $item->location->rand }})
									@endif 
								</td>
								<td> 
									@if(!$item->id_location && @$item->user->agent_code)
										{{ $item->user->agent->name }} {{ $item->user->agent->lastname }}
									@elseif($item->id_location && @$item->location->agent_code)
										{{ $item->location->agent->name }} {{ $item->location->agent->lastname }}
									@endif   
								</td> 
								<td class="nw ac">
									{{ $item->total_amount }}
								</td> 
								<td class="nw ac">
									{{ $item->location_amount+$item->amount }}
								</td> 
								@foreach($percents as $percent)
									@php($value = percent($item->total_amount, @$tipPercents[$percent->id]->percent))
									<td class="ac percent-item" 
									    data-percent-id="{{ $percent->id }}" 
									    data-percent-value="{{ $value }}"
									    align="center">
										{{ $value }}
									</td>  
								@endforeach
							</tr>
						@endforeach
					</tbody> 
				</table>

				<script>
					$(document).ready(function(){
						var totalPercent = [];
						$('.percent-item').each(function(){
							var id = $(this).data('percent-id');
							var value = parseFloat($(this).data('percent-value')); 
							totalPercent[id] = totalPercent[id] ? totalPercent[id] + value : value; 
						});
						 
						$.each(totalPercent, function(id, value){
							$('.percent-total-' + id).text(priceString(value, 4));
						});
					}); 
				</script>
			@else
				<div class="alert alert-warning">Нет зачислений</div>
			@endif
	   	</div>
	</div> 
@stop


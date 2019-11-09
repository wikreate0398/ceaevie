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
	   			<table class="table table-bordered" style="margin-bottom: 30px;">
	   				<thead>
	   					<tr>
	   						<th class="ac">Всего руб.</th>
							<th class="ac" align="center">Чаевые <br> официанту руб.</th> 
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
					<tbody>
						<tr> 
							<th>Дата</th>
							<th class="nw">№ Перевода</th>  
							<th class="nw">Официант</th> 
							<th>Всего руб.</th>
							<th class="ac">Чаевые <br>официанту руб.</th> 
							@foreach($percents as $percent)
								<th class="nw ac">{{ $percent->name }}</th>  
							@endforeach
						</tr>
					</tbody>
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
									{{ $item->user->name }} {{ $item->user->lastname }} ({{ $item->user->rand }})
								</td> 
								<td class="nw ac">
									{{ $item->total_amount }}
								</td> 
								<td class="nw ac">
									{{ $item->amount }}
								</td> 
								@foreach($percents as $percent)
									@php($value = percent($item->amount, @$tipPercents[$percent->id]->percent))
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
					var totalPercent = [];
					$('.percent-item').each(function(){
						var id = $(this).data('percent-id');
						var value = parseFloat($(this).data('percent-value'));
						 
						totalPercent[id] = totalPercent[id] ? totalPercent[id] + value : value; 
					});
					 
					$.each(totalPercent, function(id, value){
						$('.percent-total-' + id).text(priceString(value, 4));
					});

					function number_format(e,n,t,i){e=(e+"").replace(/[^0-9+\-Ee.]/g,"");var r=isFinite(+e)?+e:0,a=isFinite(+n)?Math.abs(n):0,o="undefined"==typeof i?",":i,d="undefined"==typeof t?".":t,u="",f=function(e,n){var t=Math.pow(10,n);return""+(Math.round(e*t)/t).toFixed(n)};return u=(a?f(r,a):""+Math.round(r)).split("."),u[0].length>3&&(u[0]=u[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,o)),(u[1]||"").length<a&&(u[1]=u[1]||"",u[1]+=new Array(a-u[1].length+1).join("0")),u.join(d)}

					function priceString(price, a){
					    var a = a ? a : 2;
					    if (!price) {
					        return '0';
					    } 
					    return number_format(price, a, '.', ' ');
					} 
				</script>
			@else
				<div class="alert alert-warning">Нет зачислений</div>
			@endif
	   	</div>
	</div> 
@stop

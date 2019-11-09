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
		      	<table class="table table-bordered">
					<tbody>
					<tr> 
						<th>Дата</th>
						<th class="nw">№ Перевода</th>  
						<th class="nw">Официант</th> 
						<th>Всего руб.</th>
						<th class="nw" align="center">Остаток руб.</th> 
						@foreach($percents as $percent)
							<th class="nw" align="center">{{ $percent->name }}</th>  
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
								<td class="nw" align="center">
									{{ $item->total_amount }}
								</td> 
								<td class="nw" align="center">
									{{ $item->amount }}
								</td>
								@php
									$feePrice = $item->total_amount - $item->amount;
								@endphp
								@foreach($percents as $percent)
									<td class="nw" align="center">
										{{ percent($item->amount, @$tipPercents[$percent->id]->percent) }}
									</td>  
								@endforeach
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<div class="alert alert-warning">Нет зачислений</div>
			@endif
	   	</div>
	</div> 
@stop


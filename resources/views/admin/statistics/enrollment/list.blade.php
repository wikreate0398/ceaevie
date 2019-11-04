@extends('layouts.admin') 
 
@section('content')
	
	<form action="" style="margin-bottom: 30px;">
		<div class="row"> 
			<input type="hidden" name="filter" value="1">
			<div class="col-md-2">
				<input type="text" class="form-control" value="{{ request()->rand }}" placeholder="Номер Транзакции" name="rand">
			</div> 
			
			@if($clients->count())
				<div class="col-md-2">
					<select name="client" class="form-control">
						<option value="0">Официанты</option>
						@foreach($clients as $client)
							<option value="{{ $client->id }}" {{ (request()->client == $client->id) ? 'selected' : '' }}>
								{{ $client->name }} {{ $client->lastname }} ({{ $client->rand }})
							</option>
						@endforeach
					</select>
				</div> 
			@endif

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
					<a href="{{ route('admin_enrollment') }}" class="btn btn-danger">Сбросить</a>
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
						<th class="nw">№ Транзакции</th> 
						<th class="nw">ID Официанта</th> 
						<th class="nw">Официант</th>
						<th class="nw">Дата зачисления</th> 
						<th class="nw" style="text-align: center;">Сумма руб.</th> 
						<th class="nw" style="text-align: center;">Комиссия руб.</th> 
						<th class="nw" style="text-align: center;">Заработано руб.</th> 
						<th class="nw">Способ оплаты</th> 
						<th style="width:5%; text-align: center"><i class="fa fa-cogs" aria-hidden="true"></i></th>
					</tr>
					</tbody>
					<tbody>
					@foreach($data as $item)
						<tr> 
							<td class="nw">
								{{ $item->rand }}
							</td> 
							<td class="nw">
								{{ $item->id_qrcode ? $item->qr_code->code : '' }}
							</td> 
							<td>
								{{ $item->user->name }} {{ $item->user->lastname }} ({{ $item->rand }})
							</td>
							<td class="nw">
								{{ $item->created_at->format('d.m.Y H:i') }}
							</td> 
							<td class="nw" align="center">
								{{ $item->total_amount }}
							</td> 
							<td align="center">
								{{ $item->total_amount - $item->amount }}
							</td>
							<td class="nw" align="center">
								{{ $item->amount }}
								@if($item->rating)
                                    <select class="rating-stars" data-readonly="true" data-current-rating="{{ $item->rating }}" autocomplete="off">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                @endif
							</td> 
							<td class="nw" align="center">
								<img src="/uploads/payment_types/{{ $item->payment->image_black_white }}" style="height: 15px;" alt=""> 
							</td> 
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
				<div class="alert alert-warning">Нет зачислений</div>
			@endif
	   	</div>
	</div> 
@stop


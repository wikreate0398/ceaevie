@extends('layouts.admin') 
 
@section('content')
	
	<form action="" style="margin-bottom: 30px;">
		<div class="row"> 
			<input type="hidden" name="filter" value="1">
			<div class="col-md-2">
				<input type="text" class="form-control" value="{{ request()->rand }}" placeholder="Номер Запроса" name="rand">
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

			@if($statuses->count())
				<div class="col-md-2">
					<select name="request_status" class="form-control">
						<option value="0">Статусы</option>
						@foreach($statuses as $status)
							<option value="{{ $status->define }}" {{ (request()->request_status == $status->define) ? 'selected' : '' }}>
								{{ $status->name_ru }}
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
					<a href="{{ route('admin_withdrawal_requests') }}" class="btn btn-danger">Сбросить</a>
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
						<th class="nw">№ Запроса</th>  
						<th class="nw">Официант</th>
						<th class="nw">Дата отправки запроса</th> 
						<th class="nw" style="text-align: center;">Коммисия %</th> 
						<th class="nw" style="text-align: center;">Сумма <br>для вывода руб.</th> 
						<th class="nw" style="text-align: center;">Номер карты</th> 
						<th class="nw" style="text-align: center;">Статус</th> 
						<th style="width:5%; text-align: center"><i class="fa fa-cogs" aria-hidden="true"></i></th>
					</tr>
					</tbody>
					<tbody>
					@foreach($data as $item)
						<tr> 
							<td class="nw">
								{{ $item->rand }}
							</td>  
							<td>
								{{ $item->user->name }} {{ $item->user->lastname }} ({{ $item->user->rand }})
							</td>
							<td class="nw">
								{{ $item->created_at->format('d.m.Y H:i') }}
							</td> 
							<td class="nw" align="center">
								{{ priceString(priceToPercent($item->amount+$item->commision, $item->commision),2) }}
							</td> 
							<td class="nw" align="center">
								{{ $item->amount }}
							</td> 
							<td align="center">
								{{ $item->card->hide_number }}
							</td>
							<td class="nw" align="center">
								@if($item->request_status == 'pending')
									<a href="{{ route('admin_status_requests', ['id' => $item->id, 'status' => 'rejected']) }}" class="btn btn-sm btn-danger confirm_link" data-confirm="Подтвердить операцию">
										Отклонить
									</a>

									<a href="{{ route('admin_status_requests', ['id' => $item->id, 'status' => 'confirmed']) }}" class="btn btn-sm btn-success confirm_link" data-confirm="Подтвердить операцию">			
										Подтвердить
									</a>
								@else
									{{ @$item->requestStatusData->name_ru }}
								@endif 
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
				<div class="alert alert-warning">Нет запросов</div>
			@endif
	   	</div>
	</div> 
@stop


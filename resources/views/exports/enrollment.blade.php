<table>
	<tbody>
		<tr> 
			<th>№ Транзакции</th> 
			<th>ID Официанта</th> 
			<th>Официант</th>
			<th>Дата зачисления</th> 
			<th>Сумма руб.</th> 
			<th>Комиссия руб.</th> 
			<th>Заработано руб.</th> 
			<th>Способ оплаты</th> 
			<th>Сервис</th>  
		</tr>
	</tbody>
	<tbody>
	@foreach($data as $item)
		<tr> 
			<td>
				{{ $item->rand }}
			</td> 
			<td>
				{{ $item->id_qrcode ? $item->qr_code->code : '' }}
			</td> 
			<td>
				@if(!$item->id_location)
					{{ $item->user->name }} {{ $item->user->lastname }} ({{ $item->user->rand }})
				@else
					{{ $item->location->institution_name }} ({{ $item->location->rand }})
				@endif 
			</td>
			<td>
				{{ $item->created_at->format('d.m.Y H:i') }}
			</td> 
			<td>
				{{ $item->total_amount }}
			</td> 
			<td>
				{{ $item->total_amount - withdrawFee($item->total_amount, $item->fee) }}
			</td>
			<td>
				{{ $item->location_amount+$item->amount }} P
				@if($item->rating)
                    {{ $item->rating }}

                    @if($item->review)
                        <p style="margin-top: 5px;">{{ $item->review }}</p>
                    @endif
                @endif
			</td> 
			<td class="nw" align="center">
				@if(@$item->id_payment == 1)
					<img src="{{ public_path('/img/visa_pay.png') }}" height="39" alt=""> 
				@else
					<img src="{{ public_path('/img/apple_google_pay.png') }}" height="39" alt=""> 
				@endif
			</td> 
			<td>
				{{ $item->payment_service_data->name }}
			</td> 
		</tr>
	@endforeach
	</tbody>
</table>
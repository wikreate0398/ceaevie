<table>
	<tbody>
		<tr> 
			<th>№ Транзакции</th>  
			<th>Официант</th>
			<th>Дата зачисления</th> 
			<th>Коммисия %</th>
			<th>Сумма руб.</th> 
			<th>Номер карты</th> 
			<th>Статус</th> 
		</tr>
	</tbody>
	<tbody>
	@foreach($data as $item)
		<tr> 
			<td>
				{{ $item->rand }}
			</td>  
			<td>
				{{ $item->user->name }} {{ $item->user->lastname }} ({{ $item->user->rand }})
			</td>
			<td>
				{{ $item->created_at->format('d.m.Y H:i') }}
			</td> 
			<td>
				{{ priceString(priceToPercent($item->amount+$item->commision, $item->commision),2) }}
			</td> 
			<td>
				{{ $item->amount }}
			</td> 
			<td>
				{{ $item->card->hide_number }}
			</td>
			<td>
				{{ $item->statusData->name_ru }}
			</td>  
		</tr>
	@endforeach
	</tbody>
</table>
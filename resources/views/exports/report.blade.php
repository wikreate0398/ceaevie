<table>
	<thead>
		<tr> 
			<th>Дата</th>
			<th>№ Перевода</th>  
			<th>Официант</th> 
			<th>Агент</th> 
			<th>Всего руб.</th>
			<th>Чаевые руб.</th> 
			@foreach($percents as $percent)
				<th>{{ $percent->name }}</th>  
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach($orders as $item)
			@php
				$tipPercents = $item->percents->keyBy('id_percent');
			@endphp
			<tr> 
				<td>
					{{ $item->created_at->format('d.m.Y H:i:s') }}
				</td>
				<td>
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
				<td>
					{{ $item->total_amount }}
				</td> 
				<td>
					{{ $item->location_amount+$item->amount }}
				</td> 
				@foreach($percents as $percent)
					@php($value = percent($item->total_amount, @$tipPercents[$percent->id]->percent))
					<td>
						{{ $value }}
					</td>  
				@endforeach
			</tr>
		@endforeach
	</tbody> 
</table>
@extends('layouts.admin') 

@section('content')
	<div class="row">  
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Название шаблонов</th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					@foreach($data as $item)
						<tr>
							<td style="width: 99%;">{{ $item['name'] }}</td>
							<td>
								<a href="/{{ $method }}/{{ $item['id'] }}/edit/" class="btn btn-primary btn-xs">Edit</a> 
							</td>
						</tr>
					@endforeach
					 
				</tbody>
			</table> 
		</div>
	</div>
@stop


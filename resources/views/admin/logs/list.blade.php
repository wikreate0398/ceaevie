@extends('layouts.admin') 
 
@section('content')
	<div class="row">
		<div class="col-md-12">
			@foreach($data as $item)
			<div class="portlet box blue">
				<div class="portlet-title">
					<div class="caption">
						Log {{ $item->created_at->format('d.m.Y H:i:d') }}
					</div> 
					<div class="tools">
						<a href="javascript:;" onclick="Ajax.toDelete(this, '{{ $table }}', '{{ $item->id }}')"  class="remove"></a>
					</div>
				</div>
				<div class="portlet-body">
					<div class="note note-info">
						<table class="table table-bordered">
							<tr>
								<th>Action</th>
								<td>{{ $item->action }}</td>
							</tr>
							<tr>
								<th>Payment Mode</th>
								<td>{{ $item->payment_mode }}</td>
							</tr>
							<tr>
								<th>Order Rand</th>
								<td>{{ $item->order_rand }}</td>
							</tr> 
						</table>
						{{ print_arr(json_decode($item->log, true)) }}
					</div> 
				</div>
			</div> 
			@endforeach
		</div>
	</div>
@stop


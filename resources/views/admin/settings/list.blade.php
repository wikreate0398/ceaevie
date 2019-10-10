@extends('layouts.admin')

 
@section('content') 
	<div class="row">

	   	<div class="col-md-12">
	   		@if($data->count())
			<form action="/{{ $method }}/save" class="ajax__submit form-horizontal">

				{{ csrf_field() }}
				<div class="form-body">
					<table class="table table-bordered">
						@foreach($data as $item)
							<tr>
								<td style="width:1%; white-space: nowrap;"> {{ $item['name'] }}</td>
								<td>
									@if($item['type'] == 'input')
										<input type="text" name="settings[{{ $item['id'] }}]" value="{{ $item['value'] }}" class="form-control">
									@elseif($item['type'] == 'checkbox')
										<input @if($item['value']) checked @endif
											   type="checkbox"
											   class="action-switch"
											   data-size="small"
											   name="settings[{{ $item['id'] }}]">
									@elseif($item['type'] == 'image')
										@if(!empty($item['value'])  && file_exists(public_path() . '/uploads/uploads/' .$item['value']))
											<input class="target_image_{{ $item['id'] }}" type="hidden" name="settings[<?=$item['id']?>]" value="<?=$item['value']?>">
										@endif
											@include('admin.utils.image', [
												'inputName' => $item['var'],
												'table'     => $table,
												'folder'    => $folder,
												'id'        => $item['id'],
												'label'     => '&nbsp;',
												'filename'  => $item['value']])
									@endif
								</td>
							</tr>
						@endforeach
					</table>
				</div>

				<div class="form-actions">
					<div class="btn-set pull-left">
						<button type="submit" class="btn green">Сохранить</button>
					</div>
				</div>
			</form>
			@else
				<div class="alert alert-warning">Список настроек пуст</div>
			@endif
		</div>
	</div>
@stop


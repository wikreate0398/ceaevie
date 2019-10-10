@extends('layouts.admin')

@section('content')
<div class="row">
	<div class="col-md-12"> 
		<div class="portlet light bg-inverse"> 
			<div class="portlet-body form"> 
		<div class="tabbable-line">   
			<form action="/{{ $method }}/{{ $data['id'] }}/update" class="ajax__submit form-horizontal">  

				{{ csrf_field() }}
				
				<div class="form-body" style="padding-top: 20px;"> 
					<div class="tab-content">
						@include('admin.utils.input', ['label' => 'Название', 'name' => 'name', 'req' => true, 'data' => $data]) 
					 	@include('admin.utils.input', ['label' => 'Сокращенное название', 'name' => 'short', 'req' => true, 'data' => $data])  
					</div> 
				</div>
				<div class="form-actions">
					<div class="btn-set pull-left"> 
						<button type="submit" class="btn green">Сохранить</button> 
					</div> 
				</div>
			</form>
		</div>
		</div>
		</div>
	</div>
</div>
 
@stop
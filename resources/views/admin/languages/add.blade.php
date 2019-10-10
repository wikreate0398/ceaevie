@extends('layouts.admin')

@section('content')
<div class="row">
	<div class="col-md-12"> 
		<div class="portlet light bg-inverse"> 
			<div class="portlet-body form"> 
		<div class="tabbable-line">  
 
			<form action="/{{ $method }}/create" class="ajax__submit form-horizontal">  

				{{ csrf_field() }}

				<div class="form-body" style="padding-top: 20px;"> 
					<div class="tab-content">
						@include('admin.utils.input', ['label' => 'Название', 'req' => true, 'name' => 'name']) 
						@include('admin.utils.input', ['label' => 'Сокращенное название', 'req' => true, 'name' => 'short'])  
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
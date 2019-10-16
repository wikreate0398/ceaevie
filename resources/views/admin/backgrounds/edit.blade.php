@extends('layouts.admin')

@section('content')
<div class="row">
	<div class="col-md-12"> 
	 <div class="portlet light bg-inverse"> 

			<div class="portlet-title"> 
	            @include('admin.utils.language_switcher') 
	         </div>

			<div class="portlet-body form">
			 
 
			<form action="/{{ $method }}/{{ $data['id'] }}/update" class="ajax__submit form-horizontal">  

				{{ csrf_field() }}
				
				<div class="form-body" style="padding-top: 20px;">   
					<div class="form-group">
                     	<label class="col-md-12 control-label">Цвет подложки</label>
                     	<div class="col-md-12">
                         <input type="text" name="color" id="hue-demo" class="form-control demo" data-control="hue" value="{{ $data->color }}">
                     	</div>
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
 
@stop
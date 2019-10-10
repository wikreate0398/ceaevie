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
						@include('admin.utils.input', ['label' => 'Имя', 'req' => true, 'name' => 'name', 'data' => $data])
						@include('admin.utils.input', ['label' => 'Телефон', 'name' => 'phone', 'data' => $data])
						@include('admin.utils.input', ['label' => 'E-mail', 'req' => true, 'name' => 'email', 'data' => $data])
						@include('admin.utils.input', ['label' => 'Название компании', 'req' => true, 'name' => 'company', 'data' => $data])
						<div class="form-group">
							<label class="col-md-12 control-label">Язык: <span class="req">*</span>
							</label>
							<div class="col-md-12">
								<select class="form-control" name="lang">
									<option value="0">Выбрать...</option>
									@foreach(Language::get() as $key => $language)
										<option {{ ($language['short'] == $data['lang']) ? 'selected' : '' }} value="{{ $language['short'] }}">{{ $language["short"] }}</option>
									@endforeach
								</select>
							</div>
						</div>
						@include('admin.utils.image', [
								'inputName' => 'image',
								'table' => $table,
								'folder' => 'clients',
								'id' => $data['id'],
								'filename' => $data['image']])

						@include('admin.utils.input', ['label' => 'Новый Пароль', 'name' => 'password', 'type' => 'password', 'data' => []])
						@include('admin.utils.input', ['label' => 'Повторите Пароль', 'name' => 'repeat_password', 'type' => 'password'])

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
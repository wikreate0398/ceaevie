@extends('layouts.admin')

@section('content')
<div class="row">
	<div class="col-md-12"> 
		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li class="active">
					<a href="#tab_1" data-toggle="tab">
					Детали </a>
				</li>
				<li class="">
					<a href="#tab_2" data-toggle="tab">
					Профиль </a>
				</li>

				@if($data->verification_status != 'not_passed')
					<li>
						<a href="#tab_3" data-toggle="tab">
							Верификация 
							@if($data->verification_status == 'pending')
								<i class="fa fa-exclamation-circle" aria-hidden="true" style="color: red;"></i>
							@endif
						</a>
					</li> 
				@endif
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="tab_1"> 
					<div class="portlet blue-hoki box">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-info-circle" aria-hidden="true"></i> Данные пользователя
							</div> 
						</div>
						<div class="portlet-body">
							<div class="row static-info">
								<div class="col-md-5 name">
									№:
								</div>
								<div class="col-md-7 value">
									{{ $data->rand }}
								</div>
							</div> 
							<div class="row static-info">
								<div class="col-md-5 name">
									Фио:
								</div>
								<div class="col-md-7 value">
									{{ $data->name }} {{ $data->lastname }}
								</div>
							</div>
							<div class="row static-info">
								<div class="col-md-5 name">
									E-mail:
								</div>
								<div class="col-md-7 value">
									{{ $data->email }}
								</div>
							</div>
							<div class="row static-info">
								<div class="col-md-5 name">
									Телефон:
								</div>
								<div class="col-md-7 value">
									{{ $data->phone }}
								</div>
							</div>
							<div class="row static-info">
								<div class="col-md-5 name">
									Текущий баланс:
								</div>
								<div class="col-md-7 value">
									{{ $data->ballance }} р
								</div>
							</div>
							<div class="row static-info">
								<div class="col-md-5 name">
									Процент от чаевых:
								</div>
								<div class="col-md-7 value">
									{{ $data->fee }}
								</div>
							</div>
							<div class="row static-info">
								<div class="col-md-5 name">
									Дата регистрации:
								</div>
								<div class="col-md-7 value">
									{{ $data->created_at->format('d.m.Y H:i') }}
								</div>
							</div>
							@if($data->last_entry)
								<div class="row static-info">
									<div class="col-md-5 name">
										Последний визит:
									</div>
									<div class="col-md-7 value">
										{{ $data->last_entry->format('d.m.Y H:i') }}
									</div>
								</div>
							@endif
							<div class="row static-info">
								<div class="col-md-5 name">
									Статус верификации:
								</div>
								<div class="col-md-7 value">
									{{ $data->verificationStatusData->name_ru }}
								</div>
							</div>

							<div class="row static-info">
								<div class="col-md-5 name">
									Активация:
								</div>
								<div class="col-md-7 value"> 
									@if($data->active)
										<label class="badge badge-success">Активирован</label>
									@else
										<label class="badge badge-success">Не активирован</label>
									@endif
								</div>
							</div>
						</div>
					</div> 
				</div>

				<div class="tab-pane" id="tab_2">
					<div class="portlet light bg-inverse"> 
						<div class="portlet-body form"> 
							<div class="tabbable-line">   
								<form action="/{{ $method }}/{{ $data['id'] }}/update" class="ajax__submit form-horizontal">  

									{{ csrf_field() }}
									
									<div class="form-body" style="padding-top: 20px;"> 
										<div class="tab-content"> 
											@include('admin.utils.input', ['label' => 'Имя', 'req' => true, 'name' => 'name', 'data' => $data])
											@include('admin.utils.input', ['label' => 'Фамилия', 'req' => true, 'name' => 'lastname', 'data' => $data])
											@include('admin.utils.input', ['label' => 'Телефон', 'name' => 'phone', 'data' => $data])
											@include('admin.utils.input', ['label' => 'E-mail', 'req' => true, 'name' => 'email', 'data' => $data])
											@include('admin.utils.input', ['label' => 'Процент', 'name' => 'fee', 'data' => $data]) 
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

				<div class="tab-pane" id="tab_3">
					<br>
					<a href="/uploads/clients/{{ $data->verification_file }}" class="fancybox">
						<img src="/uploads/clients/{{ $data->verification_file }}" style="max-width: 150px;">
					</a>
					<hr>
					@if($data->verification_status == 'pending')
						<a href="{{ route('admin_change_verification_status', ['id' => $data->id, 'status' => 'decline']) }}" class="btn btn-sm btn-danger confirm_link" data-confirm="Подтвердить операцию">
							Отклонить
						</a>

						<a href="{{ route('admin_change_verification_status', ['id' => $data->id, 'status' => 'confirm']) }}" class="btn btn-sm btn-success confirm_link" data-confirm="Подтвердить операцию">			
							Подтвердить
						</a>
					@else
						{{ $data->verificationStatusData->name_ru }}
					@endif
				</div>
			</div>
		</div>
  
	</div>
</div>
 
@stop
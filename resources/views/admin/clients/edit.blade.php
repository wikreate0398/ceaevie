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

				<li class="">
					<a href="#tab_4" data-toggle="tab">
					Банковские карты </a>
				</li>

				@if($data->type == 'user')
					<li class="">
						<a href="#tab_5" data-toggle="tab">
							Выставить счет </a>
					</li>
				@endif

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

				@if($data->type == 'user')
					<div class="tab-pane active" id="tab_5">
						@if($qr_codes->count())
							<form action="/{{ $method }}/{{ $data['id'] }}/waiter-bill" class="bill__form form-horizontal">

								{{ csrf_field() }}
								<div class="form-group">
									<label class="control-label col-md-12">Тип</label>
									<div class="col-md-12">
										<select name="type" class="form-control" onchange="selectBillType(this)">
											<option value="0">Выбрать</option>
											<option value="1">Сгенерировать ссылку</option>
											<option value="2">Отправить на почту</option>
										</select>
									</div>
								</div>

								<div class="bill-inputs" style="display: none;">
									<div class="form-group">
										<label class="control-label col-md-12">Email плательщика</label>
										<div class="col-md-12">
											<input type="text" name="email" class="form-control" autocomplete="off">
										</div>
									</div>

									@include('admin.utils.input', ['label' => 'Описание', 'name' => 'description'])
								</div>

								<div class="form-group">
									<label class="control-label col-md-12">Qr код</label>
									<div class="col-md-12">
										<select name="qr_code" class="form-control">
											<option value="0">Выбрать</option>
											@foreach($qr_codes as $qr_code)
												<option value="{{ $qr_code->code }}">
													{{ $qr_code->code }}
													@if($qr_code->id_location)
														(общий)
													@else
														(личный)
													@endif
												</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-12">Сумма</label>
									<div class="col-md-12">
										<input type="text" name="price" class="form-control number" autocomplete="off">
									</div>
								</div>
								<div class="form-actions">
									<div class="btn-set pull-left">
										<button type="submit" class="btn green">Готово</button>
									</div>
								</div>
							</form>

							<div class="link__block" style="display: none;">
								<div class="alert alert-info">
									<p class="link_label"></p>
								</div>
								<button class="btn btn-sm btn-warning" onclick="$('.link__block').hide(); $('.link_label').text(''); $('.bill__form').show();">Назад</button>
							</div>
						@else
							<div class="alert alert-warning">
								У клиента нет добавленых Qr кодов что бы сгенерировать выписку
							</div>
						@endif
					</div>

					<script>
						function selectBillType(select) {
							$('.bill-inputs').hide();
							if($(select).val() == 2) {
								$('.bill-inputs').show();
							}
						}
					</script>
				@endif

				<div class="tab-pane" id="tab_2">
					<div class="portlet light bg-inverse"> 
						<div class="portlet-body form"> 
							<div class="tabbable-line">   
								<form action="/{{ $method }}/{{ $data['id'] }}/update" class="ajax__submit form-horizontal">  

									{{ csrf_field() }}

									<input type="hidden" name="type" value="{{ $data->type }}">
									
									<div class="form-body" style="padding-top: 20px;"> 
										<div class="tab-content"> 

											<div class="institution_name" style="{{ ($data->type != 'admin') ? 'display: none;' : '' }}">
				                           		@include('admin.utils.input', ['label' => 'Название заведения', 'name' => 'institution_name']) 
				                           	</div>

		                           			<div class="fio" style="{{ ($data->type == 'admin') ? 'display: none;' : '' }}">
												@include('admin.utils.input', ['label' => 'Имя', 'req' => true, 'name' => 'name', 'data' => $data])
												@include('admin.utils.input', ['label' => 'Фамилия', 'req' => true, 'name' => 'lastname', 'data' => $data])
											</div> 
											@include('admin.utils.input', ['label' => 'Телефон', 'name' => 'phone', 'data' => $data])
											@include('admin.utils.input', ['label' => 'E-mail', 'req' => true, 'name' => 'email', 'data' => $data])
											@include('admin.utils.input', ['label' => 'Код партнера', 'name' => 'agent_code', 'data' => $data]) 

											@if($data->type == 'agent')
												@include('admin.utils.input', ['label' => 'Процент', 'name' => 'fee', 'data' => $data])
											@endif

											@if($data->type == 'user')
												<div class="form-group">
													<div class="col-md-1">
														<input type="checkbox"
															   class="make-switch" data-size="mini" {{ !empty($data['rbk']) ? 'checked' : '' }}
															   data-on-text="<i class='fa fa-check'></i>"
															   data-off-text="<i class='fa fa-times'></i>"
															   name="rbk">
													</div>
													<label class="control-label col-md-2">Rbk</label>
												</div>

												<div class="form-group">
													<div class="col-md-1">
														<input type="checkbox"
															   class="make-switch" data-size="mini" {{ !empty($data['payment_center']) ? 'checked' : '' }}
															   data-on-text="<i class='fa fa-check'></i>"
															   data-off-text="<i class='fa fa-times'></i>"
															   name="payment_center">
													</div>
													<label class="control-label col-md-2">Payment Center</label>
												</div>
											@endif

											<div class="form-group"> 
				                                <div class="col-md-1">
				                                    <input
													   type="checkbox"
													   data-size="mini"
													   class="make-switch"
													   data-on-text="<i class='fa fa-check'></i>"
													   data-off-text="<i class='fa fa-times'></i>"
													   {{ $data->special_payout ? 'checked' : '' }}  
													   name="special_payout">
				                                </div>
				                                <label class="col-md-2 control-label">Вывод с спец-счета</label>
				                           	</div> 

											@include('admin.utils.image', [
													'inputName' => 'image',
													'table' => $table,
													'folder' => 'clients',
													'id' => $data['id'],
													'filename' => $data['image']])

											<div class="usr_pass">
												@include('admin.utils.input', ['label' => 'Новый Пароль', 'name' => 'password', 'type' => 'password', 'data' => []])
												@include('admin.utils.input', ['label' => 'Повторите Пароль', 'name' => 'repeat_password', 'type' => 'password']) 
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

				<div class="tab-pane" id="tab_3">
					<br>
					@if($data->identificationFiles->count()) 
						@foreach($data->identificationFiles as $file)
							<a href="/uploads/clients/{{ $file->file }}" data-fancybox-group="gall" class="fancybox">
								<img src="/uploads/clients/{{ $file->file }}" style="max-width: 150px;">
							</a>
						@endforeach
					@endif
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

				<div class="tab-pane" id="tab_4"> 
					@if($data->cards)
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>ФИО</th>
									<th>Тип карты</th>
									<th>Номер карты</th>
									<th>Срок</th>
									<th>Дата добавления</th>
									<th>Статус</th>
								</tr>
							</thead>

							<tbody>
								@foreach($data->cards as $card)
									<tr>
										<td>{{ $card->name }}</td>
										<td>{{ ucfirst($card->type) }}</td>
										<td>{{ $encriptionService->decrypt($card->number) }}</td>
										<td>{{ $card->month }}/{{ $card->year }}</td>
										<td>{{ $card->created_at->format('d.m.Y H:i:s') }}</td>
										<td>{{ $card->deleted_at ? 'Удалена' : 'Активна' }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@endif
				</div>
			</div>
		</div>
  
	</div>
</div>
 
@stop
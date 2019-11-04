@extends('layouts.admin')

@section('content') 
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px;">
			<a href="#add_panel" class="btn btn-primary btn-sm open-area-btn">
				<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Добавить
			</a> 
		</div>

		<div class="col-md-12 area-panel" id="add_panel"> 
			<div class="portlet light bg-inverse"> 
				<div class="portlet-body form"> 
					<div class="tabbable-line">  
			 
						<form action="/{{ $method }}/create" class="ajax__submit form-horizontal">  

							{{ csrf_field() }}

							<div class="form-body" style="padding-top: 20px;"> 
								<div class="tab-content">
									@include('admin.utils.input', ['label' => 'Имя', 'req' => true, 'name' => 'name'])
									@include('admin.utils.input', ['label' => 'Фамилия', 'req' => true, 'name' => 'lastname'])
									@include('admin.utils.input', ['label' => 'Телефон', 'name' => 'phone'])
									@include('admin.utils.input', ['label' => 'E-mail', 'req' => true, 'name' => 'email'])
									@include('admin.utils.input', ['label' => 'Процент', 'name' => 'fee'])  
									@include('admin.utils.image', ['inputName' => 'image'])
									@include('admin.utils.input', ['label' => 'Пароль', 'req' => true, 'name' => 'password', 'type' => 'password']) 
									@include('admin.utils.input', ['label' => 'Повторите Пароль', 'req' => true, 'name' => 'repeat_password', 'type' => 'password']) 
								</div>
							<div class="form-actions">
								<div class="btn-set pull-left"> 
									<button type="submit" class="btn green">Сохранить</button> 
								</div> 
							</div>
						</div>
						</form>
					</div>
				</div>
			</div>
			 
		</div> 

		<div class="col-md-12">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat blue-madison">
						<div class="visual">
							<i class="fa fa-comments"></i>
						</div>
						<div class="details">
							<div class="number">
								{{ $today_reg }}
							</div>
							<div class="desc">
								Зарегистрировано сегодня 
							</div>
						</div> 
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat red-intense">
						<div class="visual">
							<i class="fa fa-bar-chart-o"></i>
						</div>
						<div class="details">
							<div class="number">
								{{ $week_reg }} 
							</div>
							<div class="desc">
								Зарегистрировано за неделю 
							</div>
						</div> 
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat green-haze">
						<div class="visual">
							<i class="fa fa-shopping-cart"></i>
						</div>
						<div class="details">
							<div class="number">
								{{ $total_reg }}
							</div>
							<div class="desc"> 			
								Всего зарегистрированных
							</div>
						</div> 
					</div>
				</div> 
			</div>
		</div>

		<div class="col-md-12">

			<form action="" style="margin-bottom: 20px;"> 
				<div class="row">
					<div class="col-md-3">
						<input type="text" name="search" value="{{ @request()->search }}" class="form-control">
					</div>

					<div class="col-md-3">
						<select name="sort" class="form-control">
							<option value="0">Сортировать</option>
							<option value="all" {{ (request()->sort == 'all') ? 'selected' : '' }}>
								Все
							</option>
							<option value="active" {{ (request()->sort == 'active') ? 'selected' : '' }}>
								Активные
							</option>
							<option value="no-active" {{ (request()->sort == 'no-active') ? 'selected' : '' }}>
								Неактивные
							</option>
							
							<option value="verification-pending" {{ (request()->sort == 'verification-pending') ? 'selected' : '' }}>
								В процессе идентификации
							</option> 
							
							<option value="identified" {{ (request()->sort == 'identified') ? 'selected' : '' }}>
								Идентифицированные
							</option>

							<option value="no-identified" {{ (request()->sort == 'no-identified') ? 'selected' : '' }}>
								Неидентифицированные
							</option>

							<option value="decline-identification" {{ (request()->sort == 'decline-identification') ? 'selected' : '' }}>
								Отклонены в Идентификации
							</option> 
						</select>
					</div>

					<div class="col-md-4">
						<button type="submit" class="btn btn-primary">Поиск</button>
						@if(request()->search or request()->sort)
							<a href="/{{ $method }}/" class="btn btn-danger">Сбросить</a>
						@endif
					</div> 

				</div>
			</form>

			@if($total_pending_verification)
				<div class="alert alert-warning">
					Количество пользователей ожидавших верификацию: <b>{{ $total_pending_verification }}</b>
				</div>
			@endif
			
			@if($data->count()) 
				<table class="table table-bordered">
					<tbody>
					<tr>
						<th style="width:5%; text-align: center"><i class="fa fa-check-square" aria-hidden="true"></i></th>
						<th class="nw">№</th>
						<th class="nw">ФИО</th>
						<th>E-mail</th>
						<th>Телефон</th> 
						<th>Процент</th>
						<th>Баланс руб.</th>
						<th>Последний визит</th>
						<th>Идентификация</th>
						<th style="width:5%; text-align: center"><i class="fa fa-cogs" aria-hidden="true"></i></th>
					</tr>
					</tbody>
					<tbody id="sort-items" data-table="{{ $table }}" data-action="{{ route('sortElement') }}">
					@foreach($data as $item)
						<tr id="<?=$item['id']?>">
							<td style="width:5px; white-space: nowrap;">
								<input type="checkbox"
									   class="make-switch" data-size="mini" {{ !empty($item['active']) ? 'checked' : '' }}
									   data-on-text="<i class='fa fa-check'></i>"
									   data-off-text="<i class='fa fa-times'></i>"
									   onchange="Ajax.buttonView(this, '{{ $table }}', '{{ $item["id"] }}', 'active')">
							</td>
							<td>{{ $item->rand }}</td>
							<td class="nw">{{ $item->name }} {{ $item->lastname }}</td>
							<td>{{ $item->email }}</td>
							<td>{{ $item->phone }}</td>
							<td>{!! $item->fee ? '<label class="badge badge-danger">'.$item->fee.'</label>' : '--' !!}</td>
							<td>{{ $item->ballance }}</td>
							<td class="nw">{{ $item->last_entry ? $item->last_entry->format('d.m.Y H:i') : '' }}</td>
							<td class="nw">
								{{ $item->verificationStatusData->name_ru }}
							</td>
							<td style="width: 5px; white-space: nowrap">
								<a href="/{{ $method }}/{{ $item['id'] }}/autologin/" target="_blank" class="btn btn-primary btn-xs">
									<i class="fa fa-sign-in" aria-hidden="true"></i>
								</a>
								<a style="margin-left: 5px;" href="/{{ $method }}/{{ $item['id'] }}/edit/" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
								<a class="btn btn-danger btn-xs" data-toggle="modal" href="#deleteModal_{{ $table }}_{{ $item['id'] }}"><i class="fa fa-trash-o "></i></a>
								<!-- Modal -->
							@include('admin.utils.delete', ['id' => $item['id'], 'table' => $table])
							<!-- Modal -->
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			@else
				<div class="alert alert-warning">Нет клиентов</div>
			@endif
		</div>
	</div>
@stop


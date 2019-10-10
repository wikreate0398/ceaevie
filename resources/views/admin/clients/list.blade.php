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
									@include('admin.utils.input', ['label' => 'Телефон', 'name' => 'phone'])
									@include('admin.utils.input', ['label' => 'E-mail', 'req' => true, 'name' => 'email'])
									@include('admin.utils.input', ['label' => 'Название компании', 'req' => true, 'name' => 'company']) 
									<div class="form-group">
										<label class="col-md-12 control-label">Язык: <span class="req">*</span>
										</label>
										<div class="col-md-12">
											<select class="form-control" name="lang">
												<option value="0">Выбрать...</option>
												@foreach(Language::get() as $key => $language)
													<option value="{{ $language['short'] }}">{{ $language["short"] }}</option>
												@endforeach
											</select>
										</div>
									</div>
									@include('admin.utils.image', ['inputName' => 'image'])
									@include('admin.utils.input', ['label' => 'Пароль', 'req' => true, 'name' => 'password', 'type' => 'password']) 
									@include('admin.utils.input', ['label' => 'Повторите Пароль', 'req' => true, 'name' => 'repeat_password', 'type' => 'password']) 

									<div class="form-group"> 
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="icheck-list">
                                                    <label>
                                                        <input type="checkbox" name="send_invite" class="icheck"> Отправить приглашение
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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

			<form action="" style="margin-bottom: 20px;"> 
				<div class="row">
					<div class="col-md-3">
						<input type="text" name="search" value="{{ @request()->search }}" class="form-control">
					</div>
					<div class="col-md-4">
						<button type="submit" class="btn btn-primary">Поиск</button>
						@if(request()->search)
							<a href="/{{ $method }}/" class="btn btn-danger">Сбросить</a>
						@endif
					</div>
				</div>
			</form>
			
			@if($data->count()) 
				<table class="table table-bordered">
					<tbody>
					<tr>
						<th style="width:5%; text-align: center"><i class="fa fa-check-square" aria-hidden="true"></i></th>
						<th class="nw">Имя</th>
						<th style="width:85%;">E-mail</th>

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
							<td class="nw">{{ $item->name }}</td>
							<td>{{ $item->email }}</td>
							<td style="width: 5px; white-space: nowrap">
								@if($item->finish_registration)
									<a href="/{{ $method }}/{{ $item['id'] }}/autologin/" target="_blank" class="btn btn-primary btn-xs">
										<i class="fa fa-sign-in" aria-hidden="true"></i>
									</a>
								@endif
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


@extends('layouts.personal_profile')

@section('content')
    <div class="page-header">
		<h3 class="page-title">
    <span
        class="page-title-icon bg-gradient-danger text-white mr-2">
      <i class="mdi mdi-account"></i>
    </span> Мой профиль </h3>
	</div>
	<div class="row">
		<div class="col-md-8 grid-margin profile-data">
			
			@if(Auth::user()->verification_status != 'confirm')
				<div class="card" style="margin-bottom: 40px;">
					<div class="card-body">
						<h3 style="margin-bottom: 40px;">Подтвердить личность</h3>
						
						<form class="forms-sample row ajax__submit" action="{{ route('upload_verification_file', ['lang' => $lang]) }}">
							{{ csrf_field() }}
							<div class="form-group col-md-12">
								<p>Для подтверждения личности нужно загрузить один из следующих документов: <br> </p>
								<ul class="list-arrow">
									<li>Заграничный паспорт</li>
									<li>Водительское удостоверение (обе стороны)</li>
									<li>Гражданский паспорт или ID карта (обе стороны)</li>
								</ul>
								<p>Документ должен быть действительным и содержать следующую информацию: <br></p>
								<ul class="list-ticked">
									<li>имя и фамилию</li>
									<li>дату рождения</li>
									<li>фотографию</li>
									<li>номер документа</li>
									<li>дату выдачи</li>
									<li>дату истечения срока действия документа</li>
									<li>страну, выдавшую документ</li>
									<li>личную подпись (в гражданском паспорте, заграничном паспорте)</li>
								</ul>    
								<p>А так же Ваше фото с документом в руках.</p>
							</div>

							@if(Auth::user()->verification_status == 'pending')
								<div class="col-12">
					                <span class="d-flex align-items-center purchase-popup alert-warning" style="justify-content: space-between; margin-bottom: 0;">
					                    <p style="color: #fff;">
					                        Ваш документ в процессе обработки. Мы отправим вам уведомление когда процесс идентификации завершится.
					                    </p>  
					                </span>
					            </div>
							@else
								@if(Auth::user()->verification_status == 'decline')
									<div class="col-12" style="margin-bottom: 20px;">
						                <span class="d-flex align-items-center purchase-popup alert-danger" style="justify-content: space-between; margin-bottom: 0;">
						                    <p style="color: #fff;">
						                        Нам не удалось вас идентифицировать. Загрузите повторно другой документ содержащий необходимую информацию указанную выше.
						                    </p>  
						                </span>
						            </div>
								@endif
								<div class="form-group col-md-12"> 
			                        <input type="file" name="file" class="file-upload-default">
			                        <div class="input-group">
			                          <input type="text" class="form-control file-upload-info" placeholder="Загрузите документ">
			                          <span class="input-group-append">
			                            <button class="file-upload-browse btn btn-gradient-primary" type="button">Выбрать</button>
			                          </span>
			                        </div>
			                        <mark style="margin-top: 5px; display: inline-block;">jpg,jpeg,png &nbsp;&nbsp; размер <= 5МБ</mark>
			                  	</div>
			                  	<div class="col-md-6 col-sm-12">
									<button type="submit" class="btn btn-gradient-info btn-rounded btn-block">
										Сохранить
									</button>
								</div>
							@endif
						</form>
					</div>
				</div>
			@endif

			<div class="card">
				<div class="card-body">
					<h3 style="margin-bottom: 40px;">Редактирование профиля</h3>
					<form class="forms-sample row ajax__submit" action="{{ route('edit_userdata', ['lang' => $lang]) }}">
						{{ csrf_field() }}
						<div class="form-group col-md-6">
							<label for="nameInput1">Ваше имя <span class="req">*</span> <span>(Отображается при оплате)</span></label>
							<input type="text" class="form-control"
							       id="nameInput1"
							       name="name" 
							       value="{{ Auth::user()->name }}" 
							       placeholder="Ваше имя">
						</div>
						<div class="form-group col-md-6">
							<label
								for="lastNameInput1">Фамилия <span class="req">*</span></label>
							<input type="text" class="form-control"
							       id="lastNameInput1"
							       name="lastname" 
							       value="{{ Auth::user()->lastname }}" 
							       placeholder="Фамилия">
						</div>
						
						<div class="form-group col-md-6">
							<label
								for="phoneInput1">Телефон <span class="req">*</span></label>
							<input type="text" class="form-control"
							       id="phoneInput1"
							       name="phone" 
							       value="{{ Auth::user()->phone }}" 
							       placeholder="+ 7 901 1234567">
						</div>
						<div class="form-group col-md-6">
							<label for="emailInput1">E-mail
								(login) <span class="req">*</span></label>
							<input type="email" class="form-control"
							       id="emailInput1"
							       name="email" 
							       value="{{ Auth::user()->email }}" 
							       placeholder="login@site.ru">
						</div>
						
						<div class="form-group col-md-12">
							<label for="successMessageInput1">Подпись
								(отображается при оплате):</label>
							<input type="text" class="form-control"
							       id="successMessageInput1"
							       name="payment_signature" 
							       value="{{ Auth::user()->payment_signature }}" 
							       placeholder="Спасибо, за то что посетили нас">
						</div>
						 
						<div class="col-md-6 col-sm-12">
							<button type="submit" class="btn btn-gradient-info btn-rounded btn-block">
								Сохранить
							</button>
						</div>
					</form>
				</div>
			</div>

			<div class="card" style="margin-top: 40px;">
				<div class="card-body">
					<h3 style="margin-bottom: 40px;">Изменить пароль</h3>
					<form class="forms-sample row ajax__submit" action="{{ route('change_password', ['lang' => $lang]) }}">
						{{ csrf_field() }}
						
						<div class="form-group col-md-12">
							<label
								for="passInput1">Старый Пароль <span class="req">*</span></label>
							<input type="password" class="form-control"
							       id="passInput1"
							       name="old_password" 
							       placeholder="********">
						</div>
						<div class="form-group col-md-12">
							<label
								for="passInput2">Новый Пароль <span class="req">*</span></label>
							<input type="password" class="form-control"
							       id="passInput2"
							       name="new_password" 
							       placeholder="********">
						</div>
						<div class="form-group col-md-12">
							<label for="confirmPassInput1">Повторите
								пароль <span class="req">*</span></label>
							<input type="password" class="form-control"
							       id="confirmPassInput1"
							       name="repeat_password" 
							       placeholder="********">
						</div>
						
						<div class="col-md-6 col-sm-12">
							<button type="submit" class="btn btn-gradient-info btn-rounded btn-block">
								Сохранить
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-4 profile-photo">
			<div class="card profile">
				<div class="card-body">
					<div class="profile-photo">
						<!-- <img src="{{ imageThumb(Auth::user()->image, 'uploads/clients', 150, 150, 0) }}" alt="profile"> -->

						<!-- <div class="img-ellipse" data-toggle="modal" data-target="#myModal">+</div>  -->

						<form class="ajax__submit profile__image_form" method="POST" action="{{ route('save_avatar', ['lang' => $lang]) }}">
			    			{{ csrf_field() }}
							<div class="profile__img" style="background-image: url({{ imageThumb(Auth::user()->image, 'uploads/clients', 180, 180, 0) }});"> 
								<div class="actions__upload_photo" >
									<span class="btn-file">
									    <i class="fa fa-file-image-o" aria-hidden="true"></i> 
									</span>
									<input type="file" class="avatar__fileimage" name="image" onchange="profilePhoto(this)"> 
									<input type="hidden" name="avatar" id="avatar">  
								</div>

								<div class="preloader__image_content" style="display: none;">
									<div class="loader-inner ball-pulse"> 
		            					<div></div> 
		            					<div></div> 
		            					<div></div> 
	            					</div>
								</div>
							</div> 
						</form> 

					</div> 
				</div>
			</div>
		</div>
	</div>

	<div id="overlay"></div>

    <div class="cropper__section">
        <div class="cropper__close" onclick="$('.cropper__section, #overlay').fadeOut(150);">Закрыть</div>

        <div class="row">
            <div class="col-md-12">
                <div class="cropper__image_content">
                    <img src="" id="image__crop" alt="">
                </div>
            </div> 

            <div class="col-md-12">
                <button id="crop__btn"  type="button" class="btn btn-gradient-info mr-2">Сохранить</button> 
                <div id="result"></div>
            </div> 
        </div> 
    </div>  
     
@stop


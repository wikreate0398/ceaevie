@extends('layouts.public')

@section('content')
	<section class="bg-white pt-90 pb-90 register-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="/"><img src="/img/logo.png" alt="logo"></a>
                    <h3 class="section-header mt-50 mb-30">
                        Завершите регистрацию
                    </h3>
                    <p class="page-description">Заполните форму, чтобы получать чаевые через сервис</p>
                </div>
                <div class="col-md-6 offset-md-3">
                    <form class="ajax__submit" action="{{ route('update_registration', ['lang' => $lang]) }}">
                    	{{ csrf_field() }}

                    	<input type="hidden" name="hash" value="{{ $user->hash }}">

                        <div class="row reg-fields">
                            <div class="form-group col-12 col-sm-12 col-md-12" data-access="*">
                                <label>Email <span class="req">*</span></label>
                                <input type="email" required disabled class="form-control" value="{{ $user->user->email }}">
                            </div> 
                            <div class="form-group col-12 col-sm-12 col-md-12" data-access="*">
                                <label>Имя <span class="req">*</span></label>
                                <input type="text" required name="name" value="{{ $user->user->name }}" class="form-control" placeholder="Имя">
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-12" data-access="*">
                                <label>Фамилия <span class="req">*</span></label>
                                <input type="text" required name="lastname" value="{{ $user->user->lastname }}" class="form-control" placeholder="Фамилия">
                            </div> 
                            <div class="form-group col-12 col-sm-12 col-md-12" data-access="*">
                                <label>Телефон <span class="req">*</span></label>
                                <input type="text" required name="phone" value="{{ $user->user->phone }}" class="form-control" placeholder="Телефон">
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-12" data-access="*">
                                <label>Пароль <span class="req">*</span></label>
                                <input type="password" required name="password" class="form-control" placeholder="Пароль">
                            </div>
                            <div class="form-group col-12" data-access="*">
                                <label>Повторите пароль <span class="req">*</span></label>
                                <input type="password" required name="password_confirmation" class="form-control" placeholder="Повторите пароль">
                            </div>
                            <div class="form-group col-12 text-center" data-access="*"> 
                                <button type="submit" class="btn btn-blue">Завершить</button>
                                <a href="{{ route('show_login', ['lang' => $lang]) }}" class="btn btn-default">Войти</a>
                            </div>
                        </div>
                    </form>
                    <hr>
                    @php $terms = \Pages::pageData('terms'); @endphp
                    <p class="text-grey text-center">Нажимая "Зарегистрироваться", Вы принимаете условия 
                    	<a href="{{ setUri($terms->url) }}" target="_blank">{{ $terms["name_$lang"] }}</a></p>
                </div>
            </div>
        </div>
    </section>
@stop


@extends('layouts.public')

@section('content')
	<section class="bg-white pt-90 pb-90 register-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="/"><img src="/img/logo.png" alt="logo"></a>
                    <h3 class="section-header mt-50 mb-30">
                        Регистрация нового пользователя
                    </h3>
                    <p class="page-description">Заполните форму, чтобы получать чаевые через сервис</p>
                </div>
                <div class="col-md-6 offset-md-3">
                    <form class="ajax__submit" action="{{ route('register', ['lang' => $lang]) }}">
                    	{{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-12 col-sm-12 col-md-12">
                                <label>Email <span class="req">*</span></label>
                                <input type="email" required name="email" class="form-control" placeholder="Email">
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-12">
                                <label>Имя <span class="req">*</span></label>
                                <input type="text" required name="name" class="form-control" placeholder="Имя">
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-12">
                                <label>Пароль <span class="req">*</span></label>
                                <input type="password" required name="password" class="form-control" placeholder="Пароль">
                            </div>
                            <div class="form-group col-12">
                                <label>Повторите пароль <span class="req">*</span></label>
                                <input type="password" required name="password_confirmation" class="form-control" placeholder="Повторите пароль">
                            </div>
                            <div class="form-group col-12 text-center">
                               <!--  <button type="submit" class="btn btn-white">Войти</button> -->
                                <button type="submit" class="btn btn-blue">Зарегистрироваться</button>
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


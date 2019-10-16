@extends('layouts.public')

@section('content')
<section class="pt-90 pb-90 pay-tip-page">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 text-center">
                    <a href="/"><img src="/img/logo.png" alt="logo"></a>
                    <h3 class="section-header mt-50 mb-30">
                        Здравствуйте!
                    </h3>
                    <p class="page-description">Вам понравилось обслуживание в любимом заведении?
                    Оставьте чаевые официанту онлайн платежом при помощи его личного кода с визитки</p>
                </div>
                <div class="col-md-6 offset-md-3">
                    <form class="ajax__submit" action="">
                    	{{ csrf_field() }}
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 mb-30">
                                <input type="text" required name="" class="form-control rounded-input" onfocus="this.placeholder = ''" onblur="this.placeholder = 'XXX-X'" placeholder="XXX-X">
                            </div>
                            <div class="col-12 mb-90 text-center">
                                <button type="submit" class="btn btn-blue">Зарегистрироваться</button>
                            </div>
                        </div>
                    </form>
                    <div class="payment-sistems">
                    	<img src="/img/header-home/visa.png" alt="visa">
                    	<img src="/img/header-home/google.png" alt="google-pay">
                    	<img src="/img/header-home/apple.png" alt="apple-pay">
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop


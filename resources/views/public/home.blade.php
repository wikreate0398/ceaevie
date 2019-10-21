@extends('layouts.public')

@section('content') 
    <section class="bg-grey-red mob-bg-grey-red pt-90 mb-pt-0 mb-m-30" id="home">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h1 class="mt-80 mb-30">
                        ЧАЕВЫЕ БЕЗ НАЛИЧНЫХ <br> - ЭТО ПРОСТО!
                    </h1>
                    <p class="mb-30">Получайте чаевые, даже если у Ваших клиентов <br> нет с собой наличных</p>
                    @if(!Auth::check())
                        <a href="{{ route('registration', ['lang' => $lang]) }}" class="btn btn-red mb-30">
                            Зарегистрироваться
                        </a>
                    @endif
                    <ul class="list-inline payments-header">
                    	@foreach($payments as $payment)
							<li class="list-inline-item">
		                        <img src="/uploads/payment_types/{{ $payment->image_black_white }}" alt="">
		                    </li>
                    	@endforeach 
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="bg-phone bg-phone-header loader-v2-inner">
                        <div class="flip-square-loader mx-auto"></div>
                        <img src="/img/header-home/bg-fon-logo.png" alt="" class="logo-phone">
                        <h4>Здравствуйте!</h4>
                        <p>Вам понравилось обслуживание в любимом заведении?</p>
                        <p>Оставьте чаевые официанту онлайн платежом при помощи его личного кода с визитки</p>
                        <form class="ajax__submit" action="{{ route('set_code_home', ['lang' => $lang]) }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-12">
                                    <input type="text" name="code" class="form-control code-mask" placeholder="XXX-X">
                                </div>
                                <div class="form-group col-12 text-center">
                                    <button type="submit" class="btn btn-blue">Отправить чаевые</button>
                                </div>
                            </div>
                        </form>
                        <ul class="list-inline payment_list">
                            <li class="list-inline-item">
                                <img src="/img/header-home/bg-phone-visa.png" alt="">
                            </li>
                            <li class="list-inline-item">
                                <img src="/img/header-home/bg-phone-google.png" alt="">
                            </li>
                            <li class="list-inline-item">
                                <img src="/img/header-home/bg-phone-apple.png" alt="">
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($howWork->count())
	    <section class="bg-white pt-90 pb-90" id="how-it-works">
	        <div class="container">
	            <div class="row">
	                <div class="col-md-12">
	                    <h3 class="section-header mb-90">
	                        Как это работает?
	                    </h3>
	                </div>
	                @foreach($howWork as $item)
		                <div class="col-md-3">
		                    <div class="how-works">
		                        <img src="{{ imageThumb($item->image, 'uploads/how_it_work', 181, 181, 'home') }}" alt="">
		                        <h6>{{ $item["name_$lang"] }}</h6>
		                        <p>{{ $item["description_$lang"] }}</p>
		                    </div>
		                </div>
	                @endforeach 
	            </div>
	        </div>
	    </section>
    @endif

    @if($whom->count())
	    <section class="bg-grey pt-90 pb-90" id="whom">
	        <div class="container">
	            <div class="row">
	                <div class="col-md-12">
	                    <h3 class="section-header mb-90">
	                        Кому это подойдет?
	                    </h3>
	                </div> 
	                @php $i = 0; @endphp
	                @foreach($whom->chunk(4) as $item) 
		                <div class="row">
		                	<div class="col-md-4">
			                    <div class="for-someone">
			                        <img class="img-fluid" src="{{ imageThumb($item[$i]["image"], 'uploads/whom', 350, 470, 'home') }}" alt="">
			                        <h4>{{ $item[$i]["name_$lang"] }}</h4>
			                    </div>
			                </div>
			                @php $i++ @endphp  

			                @if($item->count() > 2)
			                <div class="col-md-4">
			                    <div class="row">
			                    	@if(!empty($item[$i]))
				                        <div class="col-md-12 mb-30 mob-mb-0">
				                            <div class="for-someone">
				                                <img class="img-fluid" src="{{ imageThumb($item[$i]["image"], 'uploads/whom', 350, 220, 'home') }}" alt="">
				                                <h4>{{ $item[$i]["name_$lang"] }}</h4>
				                            </div>
				                        </div>
				                       	@php $i++ @endphp  
			                        @endif
			                        @if(!empty($item[$i]))
				                        <div class="col-md-12">
				                            <div class="for-someone">
				                                <img class="img-fluid" src="{{ imageThumb($item[$i]["image"], 'uploads/whom', 350, 220, 'home') }}" alt="">
				                                <h4>{{ $item[$i]["name_$lang"] }}</h4>
				                            </div>
				                        </div>
				                        @php $i++ @endphp  
			                        @endif
			                    </div>
			                </div>
			                @endif

			                @if(!empty($item[$i]))
				                <div class="col-md-4">
				                    <div class="for-someone">
				                        <img class="img-fluid" src="{{ imageThumb($item[$i]["image"], 'uploads/whom', 350, 470, 'home') }}" alt="">
				                        <h4>{{ $item[$i]["name_$lang"] }}</h4>
				                    </div>
				                </div>
				                @php $i++ @endphp  
			                @endif
		                </div>
	                @endforeach
	            </div>
	        </div>
	    </section>
    @endif

    <section class="bg-white pt-90 pb-90" id="advantages">
        <div class="container">
            <div class="row">
                <div class="order-2 order-sm-1 col-10 offset-1 col-md-4 offset-md-1">
                    <div class="bg-phone bg-phone-middle">
                        <img src="/img/header-home/bg-fon-logo.png" alt="" class="logo-phone">
                        <form class="ajax__submit loader-v2-inner" action="{{ route('give_thanks', ['lang' => $lang]) }}"> 
                            {{ csrf_field() }}
                            <div class="flip-square-loader mx-auto"></div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <input type="text" class="form-control code-mask" name="code" placeholder="Код официанта">
                                </div>
                                <div class="form-group col-12">
                                    <input type="text" class="form-control home-price-mask" name="price" placeholder="Сумма">
                                </div>
                                <div class="form-group col-12 text-center">
                                    <button type="submit" class="btn btn-blue">Поблагодорить</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @if($advantages->count())
	                <div class="order-1 order-sm-2 col-md-6 offset-md-1">
	                    <h2 class="mb-50">Ваши преимущества в работе с нами</h2>
	                    <ul class="list-unstyled advantages">
	                    	@foreach($advantages as $advantage) 
	                        	<li> {{ $advantage["name_$lang"] }}</li>
	                        @endforeach
	                    </ul>
                        @if(!Auth::check())
                            <a href="{{ route('registration', ['lang' => $lang]) }}" class="btn btn-red">Присоединится к нам</a>
                        @endif 
	                </div>
                @endif
            </div>
        </div>
    </section>
    <section class="bg-grey pt-90 pb-90">
        <div class="container">
            <div class="row">
                <div class="col-10 offset-1">
                    <h3 class="section-header mb-90">
                        Есть вопросы? Задавайте!
                    </h3>
                    <p class="text-center mb-50">
                        Мы всегда рады пообщаться, оставьте контакты и мы Вам перезвоним.
                    </p>
                    <form class="ajax__submit" action="{{ route('questions', ['lang' => $lang]) }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-12 col-sm-6 col-md-6">
                                <input type="text" class="form-control" name="name" placeholder="Ваше имя">
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-6">
                                <input type="text" class="form-control" name="phone" placeholder="Ваш телефон">
                            </div>
                            <div class="form-group col-12">
                                <input type="text" class="form-control" name="message" placeholder="Введите текст вашего вопроса">
                            </div>
                            <div class="form-group col-12 text-center">
                                <button type="submit" class="btn btn-red">Отправить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@stop


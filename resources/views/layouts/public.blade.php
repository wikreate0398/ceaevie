<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/fav.ico">
    <title>Чаевые онлайн</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.25/jquery.fancybox.min.css" /> 
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link href="/css/all.min.css" rel="stylesheet">
    <link href="/css/style.css?v={{ time() }}" rel="stylesheet" type="text/css">
    <link href="/css/main.css?v={{ time() }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/css/loader.css?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('js/bar-rating/dist/themes/fontawesome-stars-o.css') }}">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head> 

<script>
    $(document).ready(function(){
        @if(request()->toggle)
            scrollToBlock('#{{ request()->toggle }}'); 
        @endif
    });
</script> 
<body class="{{ (@$page_data->page_type != 'home') ? 'no-home-page' : '' }} {{ (in_array(uri(2), ['registration']) or request()->getHost() != config('app.base_domain')) ? 'no-nav' : '' }}">
    <nav class="navbar navbar-expand-md bg-grey-red no-mob-bg-grey-red pt-45 justify-content-center">
        <div class="container">
            <a href="/" class="navbar-brand">
                <img src="/img/header-home/logo.png" class="logo-lg" alt=""> 
                <img src="/img/logo-mob.png" alt="" class="logo-sm"> 
            </a>
            
            @if(!Auth::check())
                <!-- <a href="{{ route('workspace', ['lang' => $lang]) }}" class="mobile-auth-icon">
                    <i class="fa fa-user-circle-o" aria-hidden="true" style="font-size: 18px;"></i> 
                </a> -->
             
                <ul class="navbar-nav text-center sign-menu d-block d-sm-none">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('show_login', ['lang' => $lang]) }}" target="_blank">
                            Войти
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Оплатить чаевые</a>
                    </li>
                </ul>
            @endif

            <button class="navbar-toggler ml-1" type="button" data-toggle="collapse" data-target="#collapsingNavbar2">
                <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
            </button>
            <div class="navbar-collapse collapse justify-content-between align-items-center w-100" id="collapsingNavbar2">
                <ul class="navbar-nav mx-auto text-center"> 
                    @foreach(\Pages::top() as $menu)
                        @php
                            if($menu->toggle or @$page_data->page_type == 'home' && $menu->page_type == 'home')
                            {
                                $toggle = true;
                            }
                            if(!empty($toggle))
                            {
                                if(@$page_data->page_type == 'home')
                                {
                                    $link  = '#' . $menu->page_type;
                                    $class = 'toggle-link';
                                }
                                else
                                {
                                    $link = '/?toggle=' . $menu->page_type;
                                }
                            }
                            else
                            {
                                $link = setUri($menu->url);
                            }
                        @endphp
                        <li class="nav-item {{ (uri(2) == $menu->url or (!uri(1) or !uri(2)) && $menu->url == '/') ? 'active' : '' }}">
                            <a class="nav-link {{ @$class }}" href="{{ $link }}">{{ $menu["name_$lang"] }}</a>
                        </li> 
                    @endforeach

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('workspace', ['lang' => $lang]) }}">
                            Мой профиль
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav flex-row justify-content-center flex-nowrap d-none d-sm-block">
                    <li class="nav-item text-white"> 
                        @if(!Auth::check())
                            <a class="nav-link btn btn-sign" href="{{ route('show_login', ['lang' => $lang]) }}" target="_blank">
                                Войти
                            </a>
                            <span>Оплатить чаевые</span>
                        @else
                            <a class="top-username" href="{{ route('workspace', ['lang' => $lang]) }}">
                                <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                <span>{{ Auth::user()->name }} {{ Auth::user()->lastname }}</span>
                            </a>
                            &nbsp;&nbsp;
                            |
                            &nbsp;&nbsp;
                            <a href="{{ route('logout', ['lang' => $lang]) }}" class="logout-v1">
                                <i class="fa fa-power-off" aria-hidden="true"></i> 
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="pt-90 pb-90">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 order-3 order-sm-1">
                    <p>{{ request()->server('SERVER_NAME') }}</p>
                    <p><small>Сервис для оплаты чаевых <br> безналичным расчетом</small></p>
                    <p class="copyright mb-0">© {{ request()->server('SERVER_NAME') }}</p>
                </div>
                <div class="col-lg-4 order-2 order-sm-2">
                    <ul class="list-inline payment_list">
                        <li class="list-inline-item">
                            <img src="/img/payments/visa.png" alt="">
                        </li>
                        <li class="list-inline-item">
                            <img src="/img/payments/mastercard.png" alt="">
                        </li>
                        <li class="list-inline-item">
                            <img src="/img/payments/mir.png" alt="">
                        </li>
                    </ul>

                    <ul class="navbar-nav mx-auto text-center navbar-bottom"> 
                        @foreach(\Pages::bottom() as $menu) 
                            <li class="nav-item {{ (uri(2) == $menu->url or (!uri(1) or !uri(2)) && $menu->url == '/') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ setUri($menu->url) }}">{{ $menu["name_$lang"] }}</a>
                            </li> 
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-4 order-1 order-sm-3 social_block">
                    <p>Мы в соц. сетях:</p>
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a href="https://www.facebook.com/chaevieonline" target="_blank">
                                <img src="/img/socials/facebook.svg" alt="">
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="tg://resolve?domain=chaevieonline_bot" target="_blank"> 
                                <i class="fa fa-telegram" aria-hidden="true" style="font-size: 45px; vertical-align: middle;"></i> 
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://www.instagram.com/chaevieonline/" target="_blank">
                                <img src="/img/socials/instagram.svg" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <div id="ajax-notify">
        <div class="notify-inner"></div>
    </div> 
    
    <script src="{{ asset('js/inputmask.min.js') }}"></script> 
    <script src="https://use.fontawesome.com/7d23dee490.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.25/jquery.fancybox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script> 
    <script src="{{ asset('js/bar-rating/jquery.barrating.js') }}"></script>
    <script src="{{ asset('js/main.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/ajax.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/notify.js') }}?v={{ time() }}"></script>
</body>

</html>

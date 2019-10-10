<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">
    <title>Чаевые онлайн</title>

    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link href="/css/all.min.css" rel="stylesheet">
    <link href="/css/style.css?v={{ time() }}" rel="stylesheet" type="text/css">
    <link href="/css/main.css?v={{ time() }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/css/loader.css?v={{ time() }}">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head> 

<script>
    $(document).ready(function(){
        $(window).scroll(function(e){
            var body = e.target.body, scrollT = $(this).scrollTop(); 
            if (scrollT > 200) {
                $('.navbar').addClass('fixed-header');
                $('.fixed-header').css({
                    'top': "0",
                    'opacity': '1'
                }); 
            }else{ 
                $('.navbar').removeClass('fixed-header');
            } 
        }); 

        $('.toggle-link').click(function(e){
            e.preventDefault(); 
            scrollToBlock($(this).attr('href')); 
        });

        @if(request()->toggle)
            scrollToBlock('#{{ request()->toggle }}'); 
        @endif

        function scrollToBlock(id){
            $('html, body').animate({
                scrollTop: $(id).offset().top-75
            }, 1000);
        }
    });
</script>
@php $pageData = \Pages::pageData(); @endphp
<body class="{{ (@$pageData->page_type != 'home') ? 'no-home-page' : '' }}">
    <nav class="navbar navbar-expand-md bg-grey-red no-mob-bg-grey-red pt-45 justify-content-center">
        <div class="container">
            <a href="/" class="navbar-brand">
                <img src="/img/header-home/logo.png" alt="">
            </a>

            <ul class="navbar-nav mx-auto text-center sign-menu d-block d-sm-none">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Войти
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Оплатить чаевые</a>
                </li>
            </ul>
            <button class="navbar-toggler ml-1" type="button" data-toggle="collapse" data-target="#collapsingNavbar2">
                <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
            </button>
            <div class="navbar-collapse collapse justify-content-between align-items-center w-100" id="collapsingNavbar2">
                <ul class="navbar-nav mx-auto text-center"> 
                    @foreach(\Pages::top() as $menu)
                        @php
                            if($menu->toggle or $pageData->page_type == 'home' && $menu->page_type == 'home')
                            {
                                $toggle = true;
                            }
                            if(!empty($toggle))
                            {
                                if(@$pageData->page_type == 'home')
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
                </ul>
                <ul class="nav navbar-nav flex-row justify-content-center flex-nowrap d-none d-sm-block">
                    <li class="nav-item text-white">
                        <a class="nav-link btn btn-sign" href="#">
                            Войти
                        </a>
                        <span>Оплатить чаевые</span>
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
                    <p>ЧАЕВЫЕ-ОНЛАЙН.РФ</p>
                    <p><small>Сервис для оплаты чаевых <br> безналичным расчетом</small></p>
                    <p class="copyright mb-0">© чаевые-онлайн.рф</p>
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
                            <a href="#" target="_blank">
                                <img src="/img/socials/facebook.svg" alt="">
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" target="_blank">
                                <img src="/img/socials/vk.svg" alt="">
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" target="_blank">
                                <img src="/img/socials/instagram.svg" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/all.min.js"></script> 
    <script src="/js/main.js?v={{ time() }}"></script>
    <script src="/js/ajax.js?v={{ time() }}"></script>
    <script src="/js/notify.js?v={{ time() }}"></script>
</body>

</html>

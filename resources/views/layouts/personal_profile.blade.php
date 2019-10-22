<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport"
	      content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Личный кабинет</title>
	<!-- plugins:css -->
	<link rel="stylesheet"
	      href="{{ asset('profile_theme') }}/assets/vendors/mdi/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="{{ asset('profile_theme') }}/assets/vendors/css/vendor.bundle.base.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.25/jquery.fancybox.min.css" />
	<link  href="{{ asset('js/cropperjs/dist/cropper.css') }}" rel="stylesheet">
	<!-- endinject -->
	<!-- Plugin css for this page -->
	<!-- End plugin css for this page -->
	<!-- inject:css -->
	<!-- endinject -->
	<!-- Layout styles -->
	<link rel="stylesheet" href="{{ asset('profile_theme') }}/assets/css/style.css?v={{ time() }}">
	<link rel="stylesheet" href="{{ asset('profile_theme') }}/assets/css/main.css?v={{ time() }}">
	<link rel="stylesheet" href="/css/loader.css">
	<!-- End layout styles -->
	<link rel="shortcut icon" href="/fav.ico"> 
</head>
<body>
<div class="container-scroller">
	<!-- partial:partials/_navbar.html -->
	<nav
		class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
		<div
			class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
			<a class="navbar-brand brand-logo" href="/"><img
				src="/img/logo.png" alt="logo"/></a>
			<a class="navbar-brand brand-logo-mini" href="/"><img
				src="/img/logo.png" alt="logo"/></a>
		</div>
		<div class="navbar-menu-wrapper d-flex align-items-stretch">
			<button class="navbar-toggler navbar-toggler align-self-center"
			        type="button" data-toggle="minimize">
				<span class="mdi mdi-menu"></span>
			</button>
			
			<ul class="navbar-nav navbar-nav-right">
				<li class="nav-item nav-profile dropdown">
					<a class="nav-link"
					   href="{{ route('workspace', ['lang' => $lang]) }}">
						<div class="nav-profile-img">
							<img src="{{ imageThumb(Auth::user()->image, 'uploads/clients', 181, 181, 0) }}"
							     alt="image">
							<span class="availability-status online"></span>
						</div>
						<div class="nav-profile-text">
							<p class="mb-1 text-black">{{ Auth::user()->name }} {{ Auth::user()->lastname }}</p>
						</div>
					</a>
				 
				</li>
				<li class="nav-item nav-logout d-none d-lg-block">
					<a class="nav-link" href="{{ route('logout', ['lang' => $lang]) }}">
						<i class="mdi mdi-power"></i>
					</a>
				</li>
			</ul>
			<button
				class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
				type="button" data-toggle="offcanvas">
				<span class="mdi mdi-menu"></span>
			</button>
		</div>
	</nav>
	<!-- partial -->
	<div class="container-fluid page-body-wrapper">
		<!-- partial:partials/_sidebar.html -->
		<nav class="sidebar sidebar-offcanvas" id="sidebar">
			<ul class="nav">
				<li class="nav-item">
					<a class="nav-link" href="{{ route('workspace', ['lang' => $lang]) }}">
						<span class="menu-title">Рабочая область</span>
						<i class="mdi mdi-home menu-icon"></i>
					</a>
				</li> 
				<li class="nav-item">
					<a class="nav-link" href="{{ route('enrollment', ['lang' => $lang]) }}">
						<span class="menu-title">
							История зачислений  
						</span>
						@php $count = \App\Models\Tips::confirmed()->where('open', '1')->count(); @endphp
						@if($count)
							<span class="num-span">{{ $count }}</span>
						@endif
						<i class="mdi mdi-chart-line menu-icon"></i>
					</a>
				</li> 
			<!-- 	<li class="nav-item">
					<a class="nav-link" href="{{ route('ballance', ['lang' => $lang]) }}">
						<span class="menu-title">Мой баланс</span>
						<i class="mdi mdi-currency-usd menu-icon"></i>
					</a>
				</li> -->
				<li class="nav-item">
					<a class="nav-link" href="{{ route('account', ['lang' => $lang]) }}">
						<span class="menu-title">Мой профиль</span>
						<i class="mdi mdi-account menu-icon"></i>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('contact', ['lang' => $lang]) }}">
						<span class="menu-title">Связаться с нами</span>
						<i class="mdi mdi-phone-in-talk menu-icon"></i>
					</a>
				</li>
			</ul>
		</nav>
		<!-- partial -->
		<div class="main-panel {{ in_array(uri(3), ['account', 'contact-us']) ? 'profile-page' : '' }}">
			<div class="content-wrapper">

				@if(\Session::has('lk_success'))
					<div class="row" id="proBanner">
						<div class="col-12">
	                        <span class="d-flex align-items-center purchase-popup" style="justify-content: space-between;">
			                  <p>{{ \Session::get('lk_success') }}</p>
			              
			                  <i class="mdi mdi-close" id="bannerClose"></i>
			                </span>
						</div>
					</div>
				@endif
  				 
  				@yield('content')

			</div>
			<!-- content-wrapper ends -->
			<!-- partial -->
		</div>
		<!-- main-panel ends -->
	</div>
	<!-- page-body-wrapper ends -->
	
	<!-- partial:partials/_footer.html -->
	<footer>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-4">
					<img src="{{ asset('profile_theme') }}/assets/images/logo-white.png" alt="logo">
					<p>Сервис для оплаты чаевых <br> безналичным расчетом</p>
					<p class="small">© чаевые-онлайн.рф</p>
				</div>
				<div class="col-md-4 text-center align-center">
					<div class="partners">
						<img src="{{ asset('profile_theme') }}/assets/images/socials/visa-big.png" alt="visa">
						<img src="{{ asset('profile_theme') }}/assets/images/socials/mastercard.png" alt="mastercard">
						<img src="{{ asset('profile_theme') }}/assets/images/socials/mir.png" alt="mir">
					</div>
					<a href="#" class="policy">Политики конфиденциальности</a>
				</div>
				<div class="col-md-4 align-center" style="align-items: flex-end;">
					<p class="medium">Мы в соц. сетях:</p>
					<div class="social_link">
						<a href="#"><img src="{{ asset('profile_theme') }}/assets/images/socials/facebook.png" alt="facebook"></a>
						<a href="#"><img src="{{ asset('profile_theme') }}/assets/images/socials/vk.png" alt="vk"></a>
						<a href="#"><img src="{{ asset('profile_theme') }}/assets/images/socials/instagram.png" alt="instagram"></a>
					</div>
				</div>
			</div>
		</div>
	</footer>
	
</div>
<!-- container-scroller -->
<!-- plugins:js -->
<script src="{{ asset('profile_theme') }}/assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{ asset('profile_theme') }}/assets/vendors/chart.js/Chart.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('profile_theme') }}/assets/js/off-canvas.js"></script>
<script src="{{ asset('profile_theme') }}/assets/js/hoverable-collapse.js"></script>
<script src="{{ asset('profile_theme') }}/assets/js/misc.js"></script>
<!-- endinject -->
<!-- Custom js for this page -->
<script src="{{ asset('profile_theme') }}/assets/js/dashboard.js"></script>
<script src="{{ asset('profile_theme') }}/assets/js/todolist.js"></script>
<!-- End custom js for this page -->

<script src="/js/ajax.js?v={{ time() }}"></script>
<script src="/js/notify.js?v={{ time() }}"></script>
<script src="{{ asset('profile_theme') }}/assets/js/main.js?v={{ time() }}"></script>

<script src="{{ asset('js/cropperjs/dist/cropper.js') }}"></script>
<script src="https://use.fontawesome.com/7d23dee490.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.25/jquery.fancybox.min.js"></script>
<script src="{{ asset('profile_theme') }}/assets/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

@if($lang == 'ru')
<script src="{{ asset('profile_theme') }}/assets/js/bootstrap-datepicker.ru.js"></script>
@endif

<div id="ajax-notify">
    <div class="notify-inner"></div>
</div>
 
</body>
</html>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Авторизация</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.25/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="{{ asset('profile_theme') }}/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('profile_theme') }}/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('profile_theme') }}/assets/css/style.css">
    <link rel="stylesheet" href="{{ asset('profile_theme') }}/assets/css/main.css">
    <link rel="stylesheet" href="/css/loader.css?v={{ time() }}">
    <!-- End layout styles -->
  <!--   <link rel="shortcut icon" href="{{ asset('profile_theme') }}/assets/images/favicon.png" /> -->
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <a href="/">
                      <img src="/img/logo.png" alt="logo">
                  </a>
                </div>
                <div id="auth-inner">
                    <h4>Авторизация</h4>
                    <form class="ajax__submit pt-3" action="{{ route('login', ['lang' => $lang]) }}">
                        {{ csrf_field() }}
                      <div class="form-group">
                        <input type="email" class="form-control form-control-lg" name="email" placeholder="E-mail">
                      </div>
                      <div class="form-group">
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="Пароль*">
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('registration', ['lang' => $lang]) }}" class="auth-link text-black">Регистрация</a>
                        <a href="#" onclick="changeForms('#auth-inner', '#forgot-inner');" class="auth-link text-black">Забыли пароль?</a>
                      </div>
                      <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-info btn-rounded btn-block" style="min-height: 55px">
                          Войти
                        </button>
                      </div>
                    </form>
                </div>

                <div id="forgot-inner" style="display: none;">
                    <h4>Восстановление пароля</h4>
                    <form class="ajax__submit pt-3" action="{{ route('send_reset_pass', ['lang' => $lang]) }}">
                        {{ csrf_field() }}
                      <div class="form-group">
                        <input type="email" class="form-control form-control-lg" name="email" placeholder="E-mail">
                      </div>
                     
                      <div class="d-flex justify-content-between align-items-center">
                        <a href="#" onclick="changeForms('#forgot-inner', '#auth-inner')" class="auth-link text-black"><i class="fa fa-angle-left" aria-hidden="true"></i> Назад</a>
                      </div>
                      <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-info btn-rounded btn-block" style="min-height: 55px">
                          Отправить
                        </button>
                      </div>
                    </form>
                </div>
                 
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('profile_theme') }}/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('profile_theme') }}/assets/js/off-canvas.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.25/jquery.fancybox.min.js"></script>
    <script src="{{ asset('profile_theme') }}/assets/js/hoverable-collapse.js"></script>
    <script src="{{ asset('profile_theme') }}/assets/js/misc.js"></script>
    <script src="/js/main.js?v={{ time() }}"></script>
    <script src="/js/ajax.js?v={{ time() }}"></script>
    <script src="/js/notify.js?v={{ time() }}"></script>
    <script src="https://use.fontawesome.com/7d23dee490.js"></script>
    <!-- endinject -->

    <div id="ajax-notify">
        <div class="notify-inner"></div>
    </div> 

  </body>
</html>
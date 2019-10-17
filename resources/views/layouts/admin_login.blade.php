<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.1
Version: 3.6
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>Авторизация</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="/admin_theme/theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="/admin_theme/theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="/admin_theme/theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/admin_theme/theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="/admin_theme/theme/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
<link href="/admin_theme/theme/assets/admin/pages/css/login3.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="/admin_theme/theme/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="/admin_theme/theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="/admin_theme/theme/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="/admin_theme/theme/assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="/admin_theme/theme/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="/admin_theme/assets/css/admin.css?v=<?=time()?>">
<!-- Main styles -->
<link href="{{ asset('css/loader.css') . '?v=' . time() }}" rel="stylesheet">

<!-- END THEME STYLES -->
<link rel="shortcut icon" href="/fav.ico">
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN LOGO -->
<div class="logo">

</div>
<!-- END LOGO -->
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form class="login-form ajax__submit" action="{{ route('admin_run_login') }}" method="post">
        {{ csrf_field() }}
         
        <div class="logo-content" style="margin-bottom: 30px; text-align: center;">
            <img src="/img/logo_ilab.png" width="150px" alt="">
        </div>
        @if ($errors->has('login'))
            <div class="alert alert-danger">{{ $errors->first('login') }}</div>
        @endif
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label-ie8 visible-ie9">E-mail</label>
            <div class="input-icon">
                <i class="fa fa-user"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="E-mail" required name="email"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Пароль</label>
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Пароль" required name="password"/>
            </div>
        </div>
        <div class="alert alert-danger" id="error-respond" style="display: none;"></div>
        <div class="form-actions" style="border-bottom:none;"> 
            <button type="submit" class="btn green-haze">
            	Войти <i class="m-icon-swapright m-icon-white"></i>
            </button>
        </div>
    </form>
    <style>
        #error-respond p{
            color:#a94442 !important;
        }
    </style>
    <!-- END LOGIN FORM -->

</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">

</div>
<!-- END COPYRIGHT -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="/admin_theme/theme/assets/global/plugins/respond.min.js"></script>
<script src="/admin_theme/theme/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<script src="/admin_theme/theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/admin_theme/theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="/admin_theme/theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/admin_theme/theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/admin_theme/theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/admin_theme/theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/admin_theme/theme/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/admin_theme/theme/assets/global/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/admin_theme/theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="/admin_theme/theme/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="/admin_theme/theme/assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
 
<!-- Main scripts -->
<script src="/admin_theme/assets/js/jquery.nestable.js?v=<?=time()?>" type="text/javascript"></script>
<script src="/admin_theme/assets/js/ajax.js?v={time()}" type="text/javascript"></script>
 <script type="text/javascript" src="/admin_theme/theme/assets/global/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
 
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {
  Metronic.init(); // init metronic core components
  Layout.init(); // init current layout 
  Demo.init();
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>

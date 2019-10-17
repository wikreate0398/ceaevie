<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>Админ панель</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- BEGIN GLOBAL MANDATORY STYLES -->
 <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/> 
<link href="{{ asset('admin_theme') }}/theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('admin_theme') }}/theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('admin_theme') }}/theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->

<link href="{{ asset('admin_theme') }}/theme/assets/admin/pages/css/todo.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" type="text/css" href="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"/>

<link rel="stylesheet" type="text/css" href="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>

   <link href="{{ asset('admin_theme') }}/theme/assets/global/plugins/icheck/skins/all.css" rel="stylesheet"/>

<link rel="stylesheet" type="text/css" href="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_theme') }}/theme/assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery-multi-select/css/multi-select.css"/>

<link rel="stylesheet" type="text/css" href="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery-tags-input/jquery.tagsinput.css"/>

<link href="/css/loader.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="{{ asset('admin_theme') }}/theme/assets/global/plugins/jstree/dist/themes/default/style.min.css"/>

<link href="{{ asset('admin_theme') }}/theme/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>

<link href="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery-minicolors/jquery.minicolors.css" rel="stylesheet" type="text/css"/>

<!-- BEGIN THEME STYLES -->
<link href="{{ asset('admin_theme') }}/theme/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="{{ asset('admin_theme') }}/theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('admin_theme') }}/theme/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="{{ asset('admin_theme') }}/theme/assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('admin_theme') }}/theme/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/> 

<link rel="stylesheet" href="{{ asset('admin_theme') }}/assets/css/admin.css?v=<?=time()?>">
<link rel="stylesheet" href="{{ asset('admin_theme') }}/assets/css/nestable.css">
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>


<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/jstree/dist/jstree.min.js"></script>
    <script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/select2/select2.min.js"></script>

   <script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/icheck/icheck.min.js"></script>
   <script src="{{ asset('admin_theme') }}/theme/assets/admin/pages/scripts/form-icheck.js"></script>
<script>   
  $(window).on('load', function() { // makes sure the whole site is loaded 
    setTimeout(function(){
      $('#status').fadeOut(350); 
   }, 300);
    $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website. 
    $('body').delay(350).css({'overflow':'visible'});
  });  

  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content'); 
  var adminArea  = '{{ config("admin.path") }}';
</script> 
 
</head>
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
 
<body class="page-header-fixed page-quick-sidebar-over-content">


<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
   <!-- BEGIN HEADER INNER -->
   <div class="page-header-inner">
      <!-- BEGIN LOGO -->
      <div class="page-logo"> 

         <a href="/{{ config('admin.path') }}" style="background-color: #f5f5f5; padding: 10px; display: flex; align-items: center; height: 100%;">
          <img src="/img/logo.png" alt="logo" style="max-height: 100%; margin:9px 0 0 0;" class="logo-default"/>
         </a>

         <div class="menu-toggler sidebar-toggler hide">
            <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
         </div>
      </div>
      <!-- END LOGO -->
      <!-- BEGIN RESPONSIVE MENU TOGGLER -->
      <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
      </a>
      <!-- END RESPONSIVE MENU TOGGLER -->
      <!-- BEGIN TOP NAVIGATION MENU -->
      <div class="top-menu"> 
         <ul class="nav navbar-nav pull-right">
            
            
            <li class="dropdown dropdown-user">
               <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                
               <span class="username username-hide-on-mobile">
                  {{ Auth::user()->name }}
               </span>
               <i class="fa fa-angle-down"></i>
               </a>
               <ul class="dropdown-menu dropdown-menu-default">
                  <li>
                     <a href="/{{ config('admin.path') }}/profile/">
                     <i class="icon-user"></i> Профиль </a>
                  </li>
                 <!--  <li>
                     <a href="/admin/settings/">
                     <i class="fa fa-cogs" aria-hidden="true"></i> Настройки </a>
                  </li> -->
                  <li>
                     <a href="{{ route('admin_logout') }}">
                     <i class="icon-key"></i> Выйти </a>
                  </li>
               </ul>
            </li>
            <!-- END USER LOGIN DROPDOWN -->
            <!-- BEGIN QUICK SIDEBAR TOGGLER -->
            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
            
            <!-- END QUICK SIDEBAR TOGGLER -->
         </ul>
      </div>
      <!-- END TOP NAVIGATION MENU -->
   </div>
   <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
   <!-- BEGIN SIDEBAR -->
   <div class="page-sidebar-wrapper">
      <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
      <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
      <div class="page-sidebar navbar-collapse collapse">
         <!-- BEGIN SIDEBAR MENU -->
         <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
         <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
         <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
         <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
         <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
         <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
         <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper">
               <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
               <div class="sidebar-toggler">
               </div>
               <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
            <?php $menu = adminMenu(); ?>
            <li class="sidebar-search-wrapper"><br></li>
            <?php foreach ($menu as $key => $value): ?>
               <?php if (!empty($value['view'])): ?> 
                  <?php 
                     $open='';
                     if($key == 'messages')
                     {
                        $open = \App\Models\Messages::where('open', '1')->count();
                        if ($open) {
                           $open = "<span class='badge badge-roundless badge-danger'>+{$open}</span>";
                        } else{
                           $open = '';
                        }
                     }elseif ($key == 'projects') {
                        $open = \App\Models\Project\Projects::where('open', '1')->where('add_from_user', '1')->count();
                        if ($open) {
                           $open = "<span class='badge badge-roundless badge-danger'>+{$open}</span>";
                        } else{
                           $open = '';
                        }
                     }
                  ?>
                  <?php if (uri(2) == $key) { $active = 'active'; }else{ $active = ''; } ?>   
                  <li class="start <?=$active?>">
                     <a href="<?=@$value['link']?>">
                        <?=$value['icon']?>
                        <span class="title"><?=$value['name']?></span>  
                        <?=$open?> 
                        <?php if (!empty($active)): ?>
                           <span class="selected"></span>
                        <?php endif ?> 
                        <?php if (!empty($value['childs'])): ?>
                           <span class="arrow open"></span>
                        <?php endif ?> 
                     </a> 
                     <?php if (!empty($value['childs'])): ?>
                        <ul class="sub-menu">
                           <?php foreach ($value['childs'] as $key2 => $value2): ?>
                               <?php
                                    $byFullUrl = \Request::path() == rtrim(trim($value2['link'], '/'), '/') ? true : false;
                               ?>
                              <?php if ($byFullUrl) { $active = 'active'; }else{ $active = ''; } ?>
                              <li class="<?=$active?>">
                                 <a href="<?=$value2['link']?>">
                                    <?=$value2['name']?>
                                    <?php if ($key2 == 'projects-list'): ?>
                                       <?=$open?> 
                                    <?php endif ?> 
                                 </a>
                              </li>
                           <?php endforeach ?>
                        </ul>
                     <?php endif ?>
                  </li>
               <?php endif ?>
            <?php endforeach ?>
         </ul>
         <!-- END SIDEBAR MENU -->
      </div>
   </div>
   <!-- END SIDEBAR -->
   <!-- BEGIN CONTENT -->

   <div class="page-content-wrapper">
      <div class="page-content">
         <!-- BEGIN PAGE HEADER-->
         <h3 class="page-title">
            <?=!empty($pageTitle) ? $pageTitle : $menu[uri(2)]['name']?>
         </h3>
         <div class="page-bar">
            <ul class="page-breadcrumb">
       
                  <li>
                     <i class="fa fa-home"></i>
                     <a href="<?=$menu[uri(2)]['link']?>"><?=$menu[uri(2)]['name']?></a>
                     <?php if (uri(3) or !empty($menu[uri(2)]['childs'])): ?>
                        <i class="fa fa-angle-right"></i>
                     <?php endif ?>
                  </li>
                  <?php if (!empty($menu[uri(2)]['childs']) && uri(3)): ?>
                     <li>
                        <a href="<?=$menu[uri(2)]['childs'][uri(3)]['link']?>" style="text-decoration:none; cursor:pointer;"><?=$menu[uri(2)]['childs'][uri(3)]['name']?></a>
                        <?php if (uri(4)): ?>
                           <i class="fa fa-angle-right"></i>
                        <?php endif ?> 
                     </li>
                     <?php if (uri(4)): ?>
                        <?php if (!empty($crumbs)): ?>
                              <?php echo $crumbs; ?>
                           <?php else: ?>
                              <li>
                              <a href="javascript:;" style="text-decoration:none; cursor:pointer;">Редактировать</a>
                           </li>
                        <?php endif ?>
                     <?php endif ?>
                  <?php elseif(uri(3)): ?>
                     <?php if (!empty($crumbs)): ?>
                           <?php echo $crumbs; ?>
                        <?php else: ?>
                           <li>
                              <a href="javascript:;" style="text-decoration:none; cursor:pointer;">Редактировать</a>
                           </li>
                     <?php endif ?>
                  <?php endif ?>  
             
            </ul>
         </div>
         <!-- END PAGE HEADER-->

         <!-- BEGIN PAGE CONTENT-->
   

            @if(Session::has('admin_flash_message'))
               <div class="row">
                  <div class="col-md-12">
                     <div class="alert alert-success" style="margin-top: 20px;">
                        <p>{{ Session::get('admin_flash_message') }}</p>
                     </div> 
                  </div>
               </div>
               @php session()->forget('admin_flash_message') @endphp
            @endif  

            @yield('content')
         <!-- END PAGE CONTENT-->
      </div>
   </div>
 
   <!-- END CONTENT -->
   <!-- BEGIN QUICK SIDEBAR -->
   <a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a>
 
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
  
   <div class="scroll-to-top">
      <i class="icon-arrow-up"></i>
   </div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/respond.min.js"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
 
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>

<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>

<script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/fuelux/js/spinner.min.js"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>

<!-- Bootstrap plugins  -->
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>  
<script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script> 
<script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<?php if (uri(5) != 'stages'): ?> <?php endif ?>
<script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script> 
<script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.ru.js"></script>

<!-- End Bootstrap plugins  -->

<script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery-tags-input/jquery.tagsinput.min.js"></script>  
 
<script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery-multi-select/js/jquery.quicksearch.js"></script> 
<script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script> 
<script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
   
<script src="{{ asset('admin_theme') }}/theme/assets/global/scripts/metronic.js" type="text/javascript"></script>

<!-- Custom Scripts -->
<script src="{{ asset('admin_theme') }}/theme/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
<script src="{{ asset('admin_theme') }}/theme/assets/admin/pages/scripts/components-pickers.js"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/admin/pages/scripts/components-form-tools.js"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/admin/pages/scripts/components-dropdowns.js"></script>
<script src="{{ asset('admin_theme') }}/theme/assets/admin/pages/scripts/components-form-tools2.js"></script> 
 
<script src="{{ asset('admin_theme') }}/theme/assets/global/plugins/jquery-minicolors/jquery.minicolors.min.js" type="text/javascript"></script>

<!-- CKeditor --> 
<script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="{{ asset('admin_theme') }}/theme/assets/global/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- Main scripts -->
<script src="{{ asset('admin_theme') }}/assets/js/jquery.nestable.js?v=<?=time()?>" type="text/javascript"></script>
<script src="{{ asset('admin_theme') }}/assets/js/ajax.js?v={{ time() }}" type="text/javascript"></script>
<script src="{{ asset('admin_theme') }}/assets/js/custom.js?v={{ time() }}" type="text/javascript"></script> 

<script>
   jQuery(document).ready(function() {    
      Metronic.init(); // init metronic core components
      Layout.init(); // init current layout
      QuickSidebar.init(); // init quick sidebar
      Demo.init(); // init demo features
      ComponentsPickers.init();
      ComponentsDropdowns.init(); 
      ComponentsFormTools2.init();  
      ComponentsFormTools.init();
       FormiCheck.init();
   });  
</script>

<style>
   [data-notify="container"] {
      z-index: 99999 !important;
   }
</style>
  
 
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
   <head>
      <title>Dashboard | Maharaja Hotel </title>
      <!-- HTML5 Shim and Respond.js IE9 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <!-- Meta -->
      <meta charset="utf-8">
      <meta name="description" content="Admin dashboard to manage everything." />
      <meta name="keywords" content="Admin dashboard to manage everything" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
      <link rel="icon" href="<?php echo adminPublicPath() ?>/images/favicon.ico" type="image/x-icon">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/jquery-ui.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/jquery.comiseo.daterangepicker.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/icon/themify-icons/themify-icons.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/icon/font-awesome/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/icon/icofont/css/icofont.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/style.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/jquery.mCustomScrollbar.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/nestable.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/sweetalert2.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/jquery.timepicker.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/jquery-ui.multidatespicker.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/bootstrap-toggle.min.css">
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/jquery/jquery.min.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/jquery-ui/jquery-ui.min.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/jquery-ui.multidatespicker.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/moment.min.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/jquery.comiseo.daterangepicker.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/popper.js/popper.min.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/bootstrap/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/bootstrap-toggle.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
      <style type="text/css">
         .btn-default {
             color: #333;
             background-color: #fff;
             border-color: #ccc;
         }
         .btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default.active, .open>.dropdown-toggle.btn-default {
             color: #333;
             background-color: #e6e6e6;
             border-color: #adadad;
         }
         .input-group .form-control{
            width: 100%;
         }
         .padding-right .card-block, .padding-right{
            padding-right: 0px !important;
         }
         .main-body .page-wrapper{
            padding: 0px;
         }
         #mediaModal .modal-dialog.modal-lg {
            width: 98%;
            max-width: 1200px;
         }
         #mediaURL, .removeFeaturedImage{
            display: none;
         }
         .setFeaturedImage, .removeFeaturedImage {
             float: right;
             height: 40px;
         }
         a.accordion-msg {
             margin-top: 20px;
             background: #ccc;
             width: 97%;
         }
         #color-accordion .accordion-desc {
             margin-top: 14px;
             padding: 0px 9px 0px 0px;
         }
         .checkbox-group {
            border: 4px solid #ccc;
            min-height: 100px;
            max-height: 200px;
            overflow-x: auto;
            padding: 5px;
         }
         .checkbox-group label {
            width: 100%;
            float: left;
            text-align: justify;
            padding: 5px;
            margin-bottom: 0px;
            background: #cccccca6;
            margin: 1px 0px;
         }
         .checkbox-group label input {
            float: right;
         }
         .checkbox .toggle.btn {
            width: 79px !important;
            height: 43px !important;
        }
         .comiseo-daterangepicker-triggerbutton {
             width: 100%;
             height: 40px;
             background: transparent;
             box-shadow: none;
             border: none;
             top: 32px;
             position: absolute;
             z-index: 9999;
         }
         .comiseo-daterangepicker-triggerbutton:focus {
             outline: none;
         }
         .ui-timepicker-standard{
            z-index: 99999 !important;
         }
         .select2-container .select2-selection--single{
            height: 48px;
         }
         .select2-container--default .select2-selection--single .select2-selection__rendered{
            background-color: transparent;
         }
      </style>
   </head>
   <body>
      <?php
      $currentUser = getCurrentUser();
      $header = getThemeOptions('header');
      ?>
      <!-- Pre-loader start -->
      <div class="theme-loader">
         <div class="loader-track">
            <div class="loader-bar"></div>
         </div>
      </div>
      <!-- Pre-loader end -->
      <div id="pcoded" class="pcoded">
      <div class="pcoded-overlay-box"></div>
      <div class="pcoded-container navbar-wrapper">
         <nav class="navbar header-navbar pcoded-header">
            <div class="navbar-wrapper">
               <div class="navbar-logo">
                  <a class="mobile-menu" id="mobile-collapse" href="#!">
                  <i class="ti-menu"></i>
                  </a>
                  <div class="mobile-search">
                     <div class="header-search">
                        <div class="main-search morphsearch-search">
                           <div class="input-group">
                              <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                              <input type="text" class="form-control" placeholder="Enter Keyword">
                              <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <a href="<?php echo route('dashboard.index') ?>">
                  <img class="img-fluid" style="height: 40px;" src="<?php echo publicPath().'/'.$header['headerlogo'] ?>" alt="Theme-Logo" />
                  </a>
                  <a class="mobile-options">
                  <i class="ti-more"></i>
                  </a>
               </div>
               <div class="navbar-container container-fluid">
                  <ul class="nav-left">
                     <li>
                        <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                     </li>
                     <li class="header-search">
                        <div class="main-search morphsearch-search">
                           <div class="input-group">
                              <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                              <input type="text" class="form-control">
                              <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                           </div>
                        </div>
                     </li>
                     <li>
                        <a href="#!" onclick="javascript:toggleFullScreen()">
                        <i class="ti-fullscreen"></i>
                        </a>
                     </li>
                  </ul>
                  <ul class="nav-right">
                     <li class="header-notification">
                        <a href="#!">
                        <i class="ti-bell"></i>
                        <span class="badge bg-c-pink"></span>
                        </a>
                        <ul class="show-notification">
                           <li>
                              <h6>Notifications</h6>
                              <label class="label label-danger">New</label>
                           </li>
                           <li>
                              <div class="media">
                                 <img class="d-flex align-self-center img-radius" src="<?php echo adminPublicPath() ?>/images/avatar-2.jpg" alt="Generic placeholder image">
                                 <div class="media-body">
                                    <h5 class="notification-user">John Doe</h5>
                                    <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                    <span class="notification-time">30 minutes ago</span>
                                 </div>
                              </div>
                           </li>
                           <li>
                              <div class="media">
                                 <img class="d-flex align-self-center img-radius" src="<?php echo adminPublicPath() ?>/images/avatar-4.jpg" alt="Generic placeholder image">
                                 <div class="media-body">
                                    <h5 class="notification-user">Joseph William</h5>
                                    <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                    <span class="notification-time">30 minutes ago</span>
                                 </div>
                              </div>
                           </li>
                           <li>
                              <div class="media">
                                 <img class="d-flex align-self-center img-radius" src="<?php echo adminPublicPath() ?>/images/avatar-3.jpg" alt="Generic placeholder image">
                                 <div class="media-body">
                                    <h5 class="notification-user">Sara Soudein</h5>
                                    <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                    <span class="notification-time">30 minutes ago</span>
                                 </div>
                              </div>
                           </li>
                        </ul>
                     </li>
                     <li class="user-profile header-notification">
                        <a href="javascript:void(0);">
                        <img src="<?php echo adminPublicPath() ?>/images/avatar-4.jpg" class="img-radius" alt="User-Profile-Image">
                        <span><?php echo $currentUser->name ?></span>
                        <i class="ti-angle-down"></i>
                        </a>
                        <ul class="show-notification profile-notification">
                           <?php
                           if (in_array($currentUser->role, ['Admin'])) {
                              ?>
                              <li>
                                 <a href="<?php echo e(route('themes.index')); ?>">
                                 <i class="ti-settings"></i> Settings
                                 </a>
                              </li>
                              <?php
                           }
                           ?>
                           <li>
                              <a href="<?php echo route('logout') ?>">
                              <i class="ti-layout-sidebar-left"></i> Logout
                              </a>
                           </li>
                        </ul>
                     </li>
                  </ul>
               </div>
            </div>
         </nav>
         <div class="pcoded-main-container">
            <div class="pcoded-wrapper">
               <nav class="pcoded-navbar">
                  <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                  <div class="pcoded-inner-navbar main-menu">
                     <div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation">Navigations</div>
                     <ul class="pcoded-item pcoded-left-item sidebaarmenu sidebaarmenuCustom">
                        <li>
                           <a href="<?php echo route('dashboard.index'); ?>">
                              <span class="pcoded-micon"><i class="ti-home"></i></span>
                              <span class="pcoded-mtext" data-i18n="nav.basic-components.main">Dashboard</span></a>
                        </li>
                        <?php
                        foreach (postTypes() as $postKey => $postValue) {
                           if (in_array($currentUser->role, $postValue['roles'])) {
                              ?>
                              <li class="pcoded-hasmenu">
                                 <a href="javascript:void(0)">
                                 <span class="pcoded-micon"><i class="<?php echo $postValue['icon']; ?>"></i></span>
                                 <span class="pcoded-mtext"  data-i18n="nav.basic-components.main"><?php echo $postValue['title']; ?></span>
                                 <span class="pcoded-mcaret"></span>
                                 </a>
                                 <ul class="pcoded-submenu">
                                    <li class="">
                                       <a href="<?php echo route('post.index', ['postType'=>$postKey]) ?>">
                                       <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                       <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">View</span>
                                       <span class="pcoded-mcaret"></span>
                                       </a>
                                    </li>
                                    <li class="">
                                       <a href="<?php echo route('post.create', ['postType'=>$postKey]) ?>">
                                       <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                       <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Add</span>
                                       <span class="pcoded-mcaret"></span>
                                       </a>
                                    </li>
                                    <?php
                                    if (!empty($postValue['taxonomy'])) {
                                       foreach ($postValue['taxonomy'] as $taxonomyKey => $taxonomyValue) {
                                         ?>
                                         <li class="">
                                            <a href="<?php echo route('taxonomy.index', ['postType'=>$postKey,'taxonomy'=>$taxonomyKey]) ?>">
                                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                            <span class="pcoded-mtext" data-i18n="nav.basic-components.alert"><?php echo $taxonomyValue['title']; ?></span>
                                            <span class="pcoded-mcaret"></span>
                                            </a>
                                         </li>
                                         <?php
                                       }
                                    }
                                    ?>
                                 </ul>
                              </li>
                              <?php
                           }
                        }
                        foreach (adminSideBarMenus() as $sidebarMenus) {
                           if (in_array($currentUser->role, $sidebarMenus['roles']) && isset($sidebarMenus['title']) && !empty($sidebarMenus['title'])) {
                           ?>
                           <li <?php echo (count($sidebarMenus['child'])>0?' class="pcoded-hasmenu"':''); ?>>
                              <a href="<?php echo $sidebarMenus['route']?>">
                                 <span class="pcoded-micon"><i class="<?php echo $sidebarMenus['icon']?>"></i></span>
                                 <span class="pcoded-mtext"  data-i18n="nav.basic-components.main"><?php echo $sidebarMenus['title']?></span>
                                 <?php
                                 if (count($sidebarMenus['child'])>0) {
                                    ?>
                                    <span class="pcoded-mcaret"></span>
                                    <?php
                                 }
                                 ?>
                              </a>
                              <?php
                              if (count($sidebarMenus['child'])>0) {
                                 ?>
                                 <ul class="pcoded-submenu">
                                    <?php
                                    foreach ($sidebarMenus['child'] as $childMenu) {
                                       ?>
                                       <li class="">
                                       <a href="<?php echo $childMenu['route']?>">
                                          <span class="pcoded-micon"><i class="<?php echo $childMenu['icon']?>"></i></span>
                                          <span class="pcoded-mtext" data-i18n="nav.basic-components.alert"><?php echo $childMenu['title']?></span>
                                          <span class="pcoded-mcaret"></span>
                                          </a>
                                       </li>
                                       <?php
                                    }
                                    ?>
                                 </ul>
                                 <?php
                              }
                              ?>
                           </li>
                           <?php
                           }
                        }

                        ?>
                     </ul>
                  </div>
               </nav>
               <div class="pcoded-content">
                  <div class="pcoded-inner-content">
                     <div class="main-body">
                        <div class="page-wrapper">

                           <?php if(Session::has('warning')): ?>
                           <div class="alert alert-warning" role="alert"><?php echo e(Session::get('warning')); ?></div>
                           <?php endif; ?>
                           <?php if(Session::has('danger')): ?>
                           <div class="alert alert-danger" role="alert"><?php echo e(Session::get('danger')); ?></div>
                           <?php endif; ?>
                           <?php if(Session::has('success')): ?>
                           <div class="alert alert-success" role="alert"><?php echo e(Session::get('success')); ?></div>
                           <?php endif; ?>
<?php /**PATH /var/www/html/maharaja-hotel/resources/views/Include/Header.blade.php ENDPATH**/ ?>
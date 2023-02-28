<!DOCTYPE HTML>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
   <head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title><?php echo (isset($title)?$title:'Home | Maharaja Hotel') ?></title>
   <link rel="shortcut icon" href="favicon.ico">
   <meta charset="utf-8">
   <meta name="description" content="Admin dashboard to manage everything." />
   <meta name="keywords" content="Admin dashboard to manage everything" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
   <base href="<?php echo url('/') ?>">
   <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet">

   <!-- Animate.css -->
   <link rel="stylesheet" href="<?php echo publicPath() ?>/front/css/animate.css">
   <!-- Icomoon Icon Fonts-->
   <link rel="stylesheet" href="<?php echo publicPath() ?>/front/css/icomoon.css">
   <!-- Bootstrap  -->
   <link rel="stylesheet" href="<?php echo publicPath() ?>/front/css/bootstrap.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/jquery-ui.css">
   <!-- Owl Carousel -->
   <link rel="stylesheet" href="<?php echo publicPath() ?>/front/css/owl.carousel.min.css">
   <link rel="stylesheet" href="<?php echo publicPath() ?>/front/css/owl.theme.default.min.css">
   <!-- Magnific Popup -->
   <link rel="stylesheet" href="<?php echo publicPath() ?>/front/css/magnific-popup.css">
   <!-- Flexslider  -->
   <link rel="stylesheet" href="<?php echo publicPath() ?>/front/css/flexslider.css">
   <!-- Flaticons  -->
   <link rel="stylesheet" href="<?php echo publicPath() ?>/front/fonts/flaticon/font/flaticon.css">
   <!-- Date Picker -->
   <link rel="stylesheet" href="<?php echo publicPath() ?>/front/css/bootstrap-datepicker.css">
   <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/icon/font-awesome/css/font-awesome.min.css">

   <link rel="stylesheet" href="<?php echo publicPath() ?>/front/css/style.css">

   <!-- Modernizr JS -->
   <script src="<?php echo publicPath() ?>/front/js/modernizr-2.6.2.min.js"></script>
   <!-- FOR IE9 below -->
   <!--[if lt IE 9]>
   <script src="js/respond.min.js"></script>
   <![endif]-->
   <!-- jQuery -->
   <script src="<?php echo publicPath() ?>/front/js/jquery.min.js"></script>
   <!-- jQuery Easing -->
   <script src="<?php echo publicPath() ?>/front/js/jquery.easing.1.3.js"></script>
   <!-- Bootstrap -->
   <script src="<?php echo publicPath() ?>/front/js/bootstrap.min.js"></script>
   <script src="<?php echo publicPath() ?>/front/js/notify.js"></script>
   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPXLdPszWCdclYabefKxQDd7hLq1VAf7Y&v=3&sensor=false&libraries=places"></script>

   <style type="text/css">
      .autocomplete-suggestion {
         padding: 5px;
         background: #fff;
         margin-bottom: 5px;
         cursor: pointer;
      }
      .autocomplete-suggestions {
          background: #ccc;
          padding: 5px;
      }
      .autocomplete-suggestion.autocomplete-selected {
          color: #fff;
          background: #999;
      }
      select option { color: black; }
      div#success-alert {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 999999999;
        display: none;
        background: #9999998f;
      }
      div#success-alert .alert-body{
        position: absolute;
        top: 10%;
        left: 0;
        right: 0;
        margin: auto;
        width: 100%;
        max-width: 500px;
        height: 100px;
      }
      a.cartButton {
          position: absolute;
          top: 0;
          right: 60px;
          height: 40px;
          width: 40px;
      }
      a.cartButton img {
          width: 100%;
          height: 100%;
      }
      a.cartButton span {
          background: #FF611F;
          width: 25px;
          height: 25px;
          display: block;
          position: absolute;
          top: -7px;
          right: -13px;
          border-radius: 50%;
          text-align: center;
          color: #fff;
      }
      <?php
      echo (isset($settings['custom_css']['custom_css_header'])?$settings['custom_css']['custom_css_header']:'');
      ?>
   </style>
   </head>
   <body>
   <div id="success-alert"> 
     <div class="alert-body">

     </div>
   </div>
   <nav id="colorlib-main-nav" role="navigation">
      <a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle active"><i></i></a>
      <div class="js-fullheight colorlib-table">
         <div class="colorlib-table-cell js-fullheight">
            <div class="row">
               <div class="col-md-6 col-md-offset-3">
                  <div class="form-group">
                     <input type="text" class="form-control" id="search" placeholder="Enter any key to search...">
                     <button type="submit" class="btn btn-primary"><i class="icon-search3"></i></button>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12 text-center">
                  <?php
                    echo getMenus('primary_menu');
                    $currentUser = getCurrentUser();
                    if ($currentUser->user_id) {
                  ?>
                  <ul>
                     <li><a href="<?php echo url('my-account') ?>">My Account</a></li>
                     <li><a href="<?php echo url('my-order') ?>">My Order</a></li>
                     <li><a href="<?php echo route('logout') ?>">Logout</a></li>
                  </ul>
                  <?php
                    } else {
                  ?>
                  <ul>
                     <li><a href="javascript:void(0);" class="loginUserBTN">Login / Register</a></li>
                  </ul>
                  <?php
                    }
                  ?>
               </div>
            </div>
         </div>
      </div>
   </nav>
   <?php
   global $settings;
   $cartData = Session::get ( 'cartData' );
   if (empty($cartData)) {
     $cartData = [];
   }
   ?>
   <div id="colorlib-page">
      <header>
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="colorlib-navbar-brand">

                        <?php
                        if (isset($settings['header']['headerlogo']) && !empty($settings['header']['headerlogo'])) {
                           ?>
                           <a href="<?php echo url('/'); ?>">
                              <img style="height: 70px;" src="<?php echo publicPath()."/".$settings['header']['headerlogo'] ?>" class="img-responsive">
                           </a>
                        <?php
                        } else {
                           ?>
                           <a class="colorlib-logo" href="<?php echo url('/'); ?>">
                              <i class="flaticon-cutlery"></i><span>Maha Raja</span><span>Hotel</span>
                           </a>
                           <?php
                        }
                        ?>
                     </a>
                  </div>
                  <?php
                 /* if (isset($settings['header']['cart_icon']) && $settings['header']['cart_icon'] == 'yes') {
                    ?>
                    <a href="<?php echo url('cart') ?>" class="cartButton"><span class="cartCount"><?php echo count($cartData) ?></span><img src="<?php echo publicPath() ?>/front/images/cart.png"></a>
                    <?php
                  }*/
                  ?>
                  <a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
               </div>
            </div>
         </div>
      </header>
<?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Include/FrontHeader.blade.php ENDPATH**/ ?>
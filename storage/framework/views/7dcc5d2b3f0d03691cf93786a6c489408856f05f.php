<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
   <head>
      <title>Maharaja</title>
      <!-- HTML5 Shim and Respond.js IE9 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <!-- Meta -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="description" content="Infiway admin dashboard to manage everything." />
      <meta name="keywords" content="Infiway admin dashboard to manage everything" />
      <link rel="icon" href="<?php echo adminPublicPath() ?>/images/favicon.ico" type="image/x-icon">
      <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/icon/themify-icons/themify-icons.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/icon/icofont/css/icofont.css">
      <link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/css/style.css">
   </head>
   <body class="fix-menu">
      <div class="theme-loader">
         <div class="loader-track">
            <div class="loader-bar"></div>
         </div>
      </div>
      <?php 
      global $settings;
      ?>
      <section class="login p-fixed d-flex text-center bg-primary common-img-bg">
         <div class="container">
            <div class="row">
               <div class="col-sm-12">
                  <div class="login-card card-block auth-body mr-auto ml-auto">
                     <?php echo Form::open(['url' => adminBasePath().'/login', 'method' => 'post', 'class' => 'md-float-material']) ?>
                        <div class="text-center">
                           <?php 
                           if (isset($settings['header']['headerlogo']) && !empty($settings['header']['headerlogo'])) {
                              ?>
                              <a href="<?php echo url('/'); ?>">
                                 <img style="height: 70px;" src="<?php echo publicPath().'/'.$settings['header']['headerlogo'] ?>" class="img-responsive">
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
                        </div>
                        <div class="auth-box">
                           <div class="row m-b-20">
                              <div class="col-md-12">
                                 <h3 class="text-left txt-primary">Sign In</h3>
                              </div>
                           </div>
                           <hr/>
                           <?php if(Session::has('warning')): ?>
                           <div class="alert alert-warning" role="alert"><?php echo e(Session::get('warning')); ?></div>
                           <?php endif; ?>
                           <?php if(Session::has('success')): ?>
                           <div class="alert alert-success" role="alert"><?php echo e(Session::get('success')); ?></div>
                           <?php endif; ?>                           
                           <div class="input-group">
                              <?php echo Form::email('user_login', old('user_login'), ['class'=>'form-control','placeholder' => 'Your Email Address','required'=>'required']); ?>
                              <span class="md-line"></span>
                           </div>
                           <div class="input-group">
                              <?php echo Form::password('password', ['class' => 'form-control','placeholder' => 'Password','required'=>'required']); ?>
                              <span class="md-line"></span>
                           </div>
                           <div class="row m-t-30">
                              <div class="col-md-12">
                                 <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Sign in</button>
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/jquery/jquery.min.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/jquery-ui/jquery-ui.min.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/popper.js/popper.min.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/bootstrap/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/jquery-slimscroll/jquery.slimscroll.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/modernizr/modernizr.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/modernizr/css-scrollbars.js"></script>
      <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/common-pages.js"></script>
   </body>
</html><?php /**PATH /home/k9c9adh99pg7/public_html/pza/resources/views/Admin/Auth/Login.blade.php ENDPATH**/ ?>
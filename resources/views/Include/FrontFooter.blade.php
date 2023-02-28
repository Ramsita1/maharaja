<?php 
global $settings;
?>
      <footer>
         <div id="footer">
            <div class="container">
               <div class="row">
                  <div class="col-md-3 col-pb-sm">
                     <h2><?php echo (isset($settings['footer']['site_about_us_title'])?$settings['footer']['site_about_us_title']:'') ?></h2>
                     <p><?php echo (isset($settings['footer']['site_about_us_description'])?$settings['footer']['site_about_us_description']:'') ?>.</p>
                     <p class="colorlib-social-icons">
                        <a href="<?php echo (isset($settings['footer']['facebook'])?$settings['footer']['facebook']:'') ?>"><i class="icon-facebook4"></i></a>
                        <a href="<?php echo (isset($settings['footer']['twitter'])?$settings['footer']['twitter']:'') ?>"><i class="icon-twitter3"></i></a>
                        <a href="<?php echo (isset($settings['footer']['twitter'])?$settings['footer']['twitter']:'') ?>"><i class="icon-googleplus"></i></a>
                        <a href="<?php echo (isset($settings['footer']['instagram'])?$settings['footer']['instagram']:'') ?>"><i class="icon-dribbble2"></i></a>
                     </p>
                  </div>
                  <div class="col-md-3 col-pb-sm">
                     <h2>Latest Blog</h2>
                     <?php 
                     $posts = getPostsByPostType('post', 3);
                     foreach ($posts as $post) {
                        ?>
                        <div class="f-entry">
                           <a href="#" class="featured-img" style="background-image: url(<?php echo publicPath() ?>/<?php echo $post->post_image ?>);"></a>
                           <div class="desc">
                              <span><?php echo $post->posted_date ?></span>
                              <h3><a href="<?php echo url('/'.$post->post_name) ?>"><?php echo $post->post_title ?></a></h3>
                           </div>
                        </div>
                        <?php
                     }
                     ?>
                     

                  </div>
                  <div class="col-md-3 col-pb-sm">
                     <h2>Instagram</h2>
                     <div class="instagram">
                        <a href="#" class="insta-img" style="background-image: url(<?php echo publicPath() ?>/front/images/dessert-1.jpg);"></a>
                        <a href="#" class="insta-img" style="background-image: url(<?php echo publicPath() ?>/front/images/dessert-2.jpg);"></a>
                        <a href="#" class="insta-img" style="background-image: url(<?php echo publicPath() ?>/front/images/dish-9.jpg);"></a>
                        <a href="#" class="insta-img" style="background-image: url(<?php echo publicPath() ?>/front/images/dish-2.jpg);"></a>
                     </div>
                  </div>
                  <div class="col-md-3 col-pb-sm">
                     <h2>Newsletter</h2>
                     <p>A small river named Duden flows by their place and supplies it with the necessary regelialia</p>
                     <div class="subscribe text-center">
                        <div class="form-group">
                           <input type="text" class="form-control text-center" placeholder="Enter Email address">
                           <input type="submit" value="Subscribe" class="btn btn-primary btn-custom">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 text-center">
                     <p>
                     <?php echo (isset($settings['footer']['footer_copy_right'])?$settings['footer']['footer_copy_right']:'') ?>
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </footer>
   
   </div>
   
   <!-- Waypoints -->
   <script src="<?php echo publicPath() ?>/front/js/jquery.waypoints.min.js"></script>
   <!-- Parallax -->
   <script src="<?php echo publicPath() ?>/front/js/jquery.stellar.min.js"></script>
   <!-- Owl Carousel -->
   <script src="<?php echo publicPath() ?>/front/js/owl.carousel.min.js"></script>
   <!-- Magnific Popup -->
   <script src="<?php echo publicPath() ?>/front/js/jquery.magnific-popup.min.js"></script>
   <script src="<?php echo publicPath() ?>/front/js/magnific-popup-options.js"></script>
   <!-- Flexslider -->
   <script src="<?php echo publicPath() ?>/front/js/jquery.flexslider-min.js"></script>
   <!-- Date Picker -->
    <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/jquery-ui/jquery-ui.min.js"></script>

   <script src="<?php echo publicPath() ?>/front/js/google_map.js"></script>

   <!-- Main JS (Do not remove) -->
   <script src="<?php echo publicPath() ?>/front/js/main.js"></script>
   <script type="text/javascript" src="<?php echo publicPath() ?>/front/js/jquery.autocomplete.js"></script>
   <script src="<?php echo publicPath() ?>/front/js/jquery.placepicker.js"></script>
   <script src="<?php echo publicPath() ?>/front/js/front-script.js"></script>
   
   @if(Session::has('warning'))
   <script>windowALert('warn', '<?php echo Session::get('warning'); ?>');</script>
   @endif
   @if(Session::has('danger'))
   <script>windowALert('error', '<?php echo Session::get('danger'); ?>');</script>
   @endif
   @if(Session::has('success'))
   <script>windowALert('success', '<?php echo Session::get('success'); ?>');</script>
   @endif
   </body>
</html>
<?php 
$currentUser = getCurrentUser();
if (!$currentUser->user_id) {
  echo view('Templates.LoginRegister', compact('post')); 
}
?>
<div class="modal fade" id="itemAttributeMOdal" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content" id="attrModalContent">
         
      </div>
   </div>
</div>
<?php 
if(isset($delivery_pickup_address) && !empty($delivery_pickup_address) && isset($cartDatas) && !empty($cartDatas))
{
   ?>
   <div class="modal fade" id="orderPreCheck" role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <div class="modal-title">Select Option</div>
            </div>
            <div class="modal-body" style="padding: 20px;">
               <div class="row">
                  <div class="col-md-6">
                     <a class="btn btn-info" href="<?php echo url('order/online?type=new') ?>">New Order</a>
                  </div>
                  <div class="col-md-6">
                     <a class="btn btn-success" href="<?php echo url('estore/items?').http_build_query($delivery_pickup_address) ?>">Continue With Exist</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <script type="text/javascript">
      jQuery(document).ready(function($) {
         $('#orderPreCheck').modal('show');
      });
   </script>
   <?php  
   if (isset($delivery_pickup_address['name'])) {
     ?>
     <script type="text/javascript">
       $('#checkout-name').trigger('change');
     </script>
     <?php
   }
   ?>
   <?php  
   if (isset($delivery_pickup_address['email'])) {
     ?>
     <script type="text/javascript">
       $('#checkout-email').trigger('change');
     </script>
     <?php
   }
   ?>
   <?php  
   if (isset($delivery_pickup_address['phone'])) {
     ?>
     <script type="text/javascript">
       $('#checkout-phone').trigger('change');
     </script>
     <?php
   }
   ?>   
   <?php
}
$cartDatas = Session::get ( 'cartData' );
$delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
if ($cartDatas && $delivery_pickup_address) {
 ?>
 <div class="modal fade" id="applyPromoCode" role="dialog">
    <div class="modal-dialog">
       <!-- Modal content-->
       <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal">&times;</button>
             <div class="modal-title">Check Deals And Apply Coupon</div>
          </div>
          <div class="modal-body" style="padding: 20px;">
              <p class="couponAlert" style="display: none;"></p>
             <div class="" style="position: relative;">
               <input type="text" name="promo_code" id="promo_code" value="<?php echo (isset($delivery_pickup_address['couponCode'])?$delivery_pickup_address['couponCode']:'') ?>" class="form-control" placeholder="Enter Promo Code">
               <button type="button" id="applyPromoCodeAction" class="btn btn-warning">Apply</button>
             </div>
             <div class="couponPromo">
              <h2>Available Deals</h2>
               <?php 
               $deals = getTodayDeals();
               if (!empty($deals) && is_array($deals)) {
                 foreach ($deals as $deal) {
                   ?>
                    <div class="coupon-box">
                     <div class="containerCoupon" style="background-color:white">
                       <h4><b><?php echo $deal->deal_title ?></b></h4> 
                       <p><?php echo $deal->deal_description ?></p>
                     
                     </div>

                     <div class="containerCoupon">
                      <?php 
                      if ($deal->end_date) {
                        ?>
                        <p class="expire">Expires: <?php echo $deal->end_date; ?></p>
                        <?php
                      }
                      ?>                     
                       <a data-dealID="<?php echo $deal->deal_id; ?>" class="btn btn-info applyDealBtn">Apply Deal</a>
                     </div>
                     </div>
                   <?php
                 }
               }
               ?>
             </div>
          </div>
       </div>
    </div>
 </div>
<?php } ?> 
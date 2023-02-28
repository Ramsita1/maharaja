<aside id="colorlib-hero">
   <div class="flexslider">
      <ul class="slides">
         <li style="background-image: url(<?php echo publicPath() ?>/front/images/bg-img.jpg);" data-stellar-background-ratio="0.5">
            
            <div class="container-fluid">
               <div class="row">
                  <div class="back-btn"><a onclick="window.history.back();" ><span class="back-ico"><img src="<?php echo publicPath() ?>/front/images/back-btn.png"></span><span class="back-txt">Back</span></a></div>
                  <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-3 slider-text">
                     <div class="slider-text-inner text-center">
                        <h2 class="page-title">Delivery Details</h2>
                        <form method="get" action="<?php echo url('estore/delivery') ?>" class="colorlib-form ordernow-form deliveryLOC">
                           <div class="row">
                              <div class="col-md-12 animate-box">
                                 <div class="form-group">
                                    <div class="radio ordernowlaterbtn" >
                                       <input type="radio" name="pickup_when" id="ordertimenow" value="Now" checked><label for="ordertimenow" class="ordernow">Now</label>
                                       <input type="radio" name="pickup_when" id="ordertimelater"  value="Later" ><label for="ordertimelater" class="orderlater">Later</label>
                                    </div>
                                 </div>
                              </div>
                              <input type="hidden" name="pincode" id="pincode" value="<?php echo old('pincode') ?>">
                              <input type="hidden" name="suburb" id="suburb" value="<?php echo old('suburb') ?>">
                              <input type="hidden" name="city" id="city" value="<?php echo old('city') ?>">
                              <input type="hidden" name="lat" id="lat" value="<?php echo old('lat') ?>">
                              <input type="hidden" name="lng" id="lng" value="<?php echo old('lng') ?>">
                              <div class="col-md-6 animate-box content-txt">
                                 <div class="form-group">
                                    <label for="unit_number">UNIT NUMBER</label>
                                    <div class="form-field">
                                       <input type="text" class="form-control" value="<?php echo old('unit_number') ?>" name="unit_number" placeholder="UNIT NUMBER">
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="col-md-6 animate-box content-txt">
                                 <div class="form-group">
                                    <label for="street_address">STREET ADDRESS</label>
                                    <div class="form-field">
                                       <input type="text" value="<?php echo old('street_address') ?>" class="form-control" id="locationPicker" required="" name="street_address" placeholder="Street">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-12 animate-box content-txt">
                                 <div class="form-group">
                                    <label for="delivery_instructions">Add Delivery instructions for Rider</label>
                                    <div class="form-field">
                                       <textarea id="delivery_instructions" rows="3" class="form-control " name="delivery_instructions" placeholder="Add Delivery instructions for Rider"><?php echo old('delivery_instructions') ?></textarea>
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="col-md-12 animate-box">
                                 <div class="row">
                                    <div class="col-md-4 col-md-offset-4">
                                       <input type="submit" name="submit" required="" id="submit" value="Next" class="btn btn-primary btn-block">
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </li>
      </ul>
   </div>
</aside>
<div class="colorlib-intro">
   <div class="container">
      <div class="row">
         <div class="col-md-3 col-sm-6 text-center">
            <div class="intro animate-box">
               <span class="icon">
               <i class="icon-map4"></i>
               </span>
               <h2>Address</h2>
               <p>198 West 21th Street, Suite 721 New York NY 10016</p>
            </div>
         </div>
         <div class="col-md-3 col-sm-6 text-center">
            <div class="intro animate-box">
               <span class="icon">
               <i class="icon-clock4"></i>
               </span>
               <h2>Opening Time</h2>
               <p>Monday - Sunday</p>
               <span>8am - 9pm</span>
            </div>
         </div>
         <div class="col-md-3 col-sm-6 text-center">
            <div class="intro animate-box">
               <span class="icon">
               <i class="icon-mobile2"></i>
               </span>
               <h2>Phone</h2>
               <p>+ 001 234 567</p>
               <p>+ 001 234 567</p>
            </div>
         </div>
         <div class="col-md-3 col-sm-6 text-center">
            <div class="intro animate-box">
               <span class="icon">
               <i class="icon-envelope"></i>
               </span>
               <h2>Email</h2>
               <p><a href="#">info@domain.com</a><br><a href="#">luto@email.com</a></p>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="colorlib-reservation reservation-page">
   <div class="container">
      <div class="row">
         <div class="col-md-6 col-md-offset-3 text-center animate-box intro-heading">
            <h2>Make A Reservation</h2>
            <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life</p>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   jQuery(document).ready(function($) {
      $('.deliveryLOC').submit(function(event) {
         if (!$('#pincode').val() || !$('#suburb').val() || !$('#city').val()) {
            windowALert('error', 'Please select Address from list');
            event.preventDefault();
         }         
      });
   });
</script><?php /**PATH C:\xampp\htdocs\pza\resources\views/Estore/Delivery.blade.php ENDPATH**/ ?>
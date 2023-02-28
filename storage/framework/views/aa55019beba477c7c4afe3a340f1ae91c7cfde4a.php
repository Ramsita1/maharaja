<style type="text/css">
   label.store_radio {
       background: #ccc;
       float: left;
       margin-left: 80px;
       padding: 10px;
       cursor: pointer;
   }
   .store_radio.active{
      background: #999;
   }
   .store_radio_input {
      opacity: 0;
   }
</style>
<aside id="colorlib-hero">
   <div class="flexslider">
      <ul class="slides">
         <li style="background-image: url(<?php echo publicPath() ?>/front/images/bg-img.jpg);" data-stellar-background-ratio="0.5">
            
            <div class="container-fluid">
               <div class="row">
                  <div class="back-btn"><a onclick="window.history.back();" ><span class="back-ico"><img src="<?php echo publicPath() ?>/front/images/back-btn.png"></span><span class="back-txt">Back</span></a></div>
                  <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-3 slider-text">
                     <div class="slider-text-inner text-center">
                        <form method="get" action="<?php echo url('estore/items') ?>" class="colorlib-form ">
                          
                           <input type="hidden" name="pickup_when" value="<?php echo $pickup_when ?>">
                           <input type="hidden" name="unit_number" value="<?php echo $unit_number ?>">
                           <input type="hidden" name="street_number" value="<?php echo $street_number ?>">
                           <input type="hidden" name="street" value="<?php echo $street ?>">
                           <input type="hidden" name="suburb" value="<?php echo $suburb ?>">
                           <input type="hidden" name="city" value="<?php echo $city ?>">
                           <input type="hidden" name="pincode" value="<?php echo $pincode ?>">
                           <input type="hidden" name="lat" value="<?php echo $lat ?>">
                           <input type="hidden" name="lng" value="<?php echo $lng ?>">
                           <input type="hidden" name="order_type" value="Delivery">
                           <div class="row">
                              <div class="col-md-12 animate-box">
                                 <div class="form-group">
                                    <?php 
                                    if (count($stores) > 1) {
                                       ?>
                                       <label for="phone">Select Store</label><br>
                                       <?php 
                                    }
                                    foreach ($stores as $store) {
                                       ?>
                                       <div class="col-md-12">                                             
                                          <label class="store_radio" for="store_<?php echo $store->store_id ?>">
                                             <input type="radio" name="store" class="store_radio_input" id="store_<?php echo $store->store_id ?>" data-store_id="<?php echo $store->store_id ?>" value="<?php echo str_replace(' ','-',$store->store_title.'-'.$store->store_id); ?>">
                                             <?php echo $store->store_title.' '.$store->store_postalCode.' '.$store->store_suburb.' '.$store->store_address ?>
                                          </label>
                                       </div>
                                       <?php 
                                    }
                                    ?>
                                    <span class="deliveryButton">
                                       <b>
                                        </b>  
                                    </span><br>                                    
                                    <label for="phone" class=" pickup_when-now" <?php echo ($pickup_when == 'Now'?'style="display:none;"':'') ?>>Sorry! this store is currently closed Pick a Date & Time for your future Order.<br>
                                    </label>
                                 </div>
                              </div>
                              <div class="col-md-6 animate-box pickup_when-now" <?php echo ($pickup_when == 'Now'?'style="display:none;"':'') ?>>
                                 <div class="form-group">
                                    <label for="date">ORDER DATE</label>
                                    <div class="form-field">
                                       <i class="icon icon-calendar2"></i>
                                       <select name="order_date" class="form-control" id="order_date_select">
                                          <option value="">Select Date</option>
                                                                                    
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6 animate-box pickup_when-now" <?php echo ($pickup_when == 'Now'?'style="display:none;"':'') ?>>
                                 <div class="form-group">
                                    <label for="time">ORDER Time</label>
                                    <div class="form-field">
                                       <i class="icon icon-arrow-down3"></i>
                                       <select name="order_time" class="form-control" id="order_time_select">
                                          <option value="">Select Time</option>  
                                                                                    
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="col-md-12 animate-box  pickup_when-now" <?php echo ($pickup_when == 'Now'?'style="display:none;"':'') ?>>
                                 <div class="row">
                                    <div class="col-md-4 col-md-offset-4">
                                       <input type="submit" name="submit" id="submit" value="Next" class="btn btn-primary btn-block">
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
<?php echo view('Templates.HeaderSliderIntro') ?>
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
      
      $('.store_radio_input').change(function(event) {
         $('.store_radio').removeClass('active');
         $(this).closest('.store_radio').addClass('active');         
         var store_id = $(this).attr('data-store_id');
         $.ajax({
            url: '<?php echo url('estore/getStoreDates') ?>',
            type: 'GET',
            data: {
               store_id: store_id,
               pickup_when: '<?php echo $pickup_when ?>',
               orderType: 'StoreOnlineOrderTimingsDelivery'
            },
         })
         .done(function(response) {
            if (response == 'currentSlotExit') {
               $('#order_date_select').html('<option value="<?php echo date('Ymd'); ?>" selected><?php echo date('Y-m-d'); ?></option>');
               $('#order_time_select').html('<option value="<?php echo date('His', strtotime("+45 minutes")); ?>" selected><?php echo date('h:i A', strtotime("+45 minutes")); ?></option>');
               $('#submit').trigger('click');
               return false;
            }
            $('.pickup_when-now').fadeIn();
            $('#order_date_select').html(response);
            $('#order_date_select').trigger('change');
         });
      });
      <?php 
      if (count($stores) == 1) {
         ?>
         $('.store_radio_input').prop('checked', true);
         $('.store_radio_input').trigger('change');
         <?php
      }
      ?>
      $('#order_date_select').change(function(event) {
         var selectedDate = $(this).val();
         var selectedDay = $(this).find(':selected').attr('data-day');
         var store_id = $('.store_radio .store_radio_input:checked').attr('data-store_id');
         var store_slug = $('.store_radio .store_radio_input:checked').val();
         $.ajax({
            url: '<?php echo url('estore/getSelectedTimes') ?>',
            type: 'GET',
            data: {
               selectedDate: selectedDate,
               selectedDay: selectedDay, 
               store_id: store_id,
               orderType: 'StoreOnlineOrderTimingsDelivery'
            },
         })
         .done(function(response) {
            if (response == 'PickupAvailable') {
               $('.deliveryButton b').html('Sorry our delivery service is closed at the movement.<br>You can choose to order later or can otp for pickup.<br><a class="btn btn-success" href="<?php echo url('/estore/items') ?>?order_type=Pickup&pickup_when=Now&store='+store_slug+'&order_date=<?php echo date('Ymd'); ?>&order_time=<?php echo date('His') ?>">Pickup Now</a>');
               $('#order_time_select').html('<option value="">Select Time</option>');
            } else {
               $('.deliveryButton b').html('');
               $('#order_time_select').html(response);
            }
            
         });
      });
   });
</script><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Estore/DeliveryStoreTiming.blade.php ENDPATH**/ ?>
<aside id="colorlib-hero">
   <div class="flexslider">
      <ul class="slides">
         <li style="background-image: url(<?php echo publicPath() ?>/front/images/bg-img.jpg);" data-stellar-background-ratio="0.5">
            
            <div class="container-fluid">
               <div class="row">
                  <div class="back-btn"><a onclick="window.history.back();" ><span class="back-ico"><img src="<?php echo publicPath() ?>/front/images/back-btn.png"></span><span class="back-txt">Back</span></a></div>
                  <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-3 slider-text">
                     <div class="slider-text-inner text-center">
                        <form method="get" action="<?php echo url('estore/items') ?>" class="colorlib-form">
                           <input type="hidden" name="store" value="<?php echo str_replace(' ','-',$store->store_title.'-'.$store->store_id) ?>">
                           <input type="hidden" name="pickup_when" value="<?php echo $pickup_when ?>">
                           <input type="hidden" name="order_type" value="Pickup">
                           <div class="row">
                              <div class="col-md-12 animate-box">
                                 <div class="form-group">
                                    <!-- <label for="phone">PICKUP FROM</label><br> -->
                                    <label for="phone"><?php //echo '<b>'.$store->store_title.'</b> '.$store->store_postalCode.' '.$store->store_suburb.' '.$store->store_address; ?></label><br>
                                       <?php 
                                       $currentDay = date('D');
                                       $currentSlotExit = false;
                                       if (isset($weekdays[$currentDay]['status']) && $weekdays[$currentDay]['status'] == 1) {
                                          $slotes = $weekdays[$currentDay];
                                          foreach (itemFor() as $itemFor) {
                                             if (isset($slotes[$itemFor]['open_time']) && !empty($slotes[$itemFor]['open_time']) && isset($slotes[$itemFor]['close_time']) && !empty($slotes[$itemFor]['close_time'])) {
                                                $open_time = date('His', strtotime($slotes[$itemFor]['open_time']));
                                                $close_time = date('His', strtotime($slotes[$itemFor]['close_time']));
                                                if ($open_time < date('His') && $close_time > date('His')) {
                                                   $currentSlotExit = true;
                                                }
                                             }
                                          }
                                       } 
                                       $deliverySlot = false;
                                       if (!$currentSlotExit) {
                                          
                                          $storeTimings = \App\StoreOnlineOrderTimings::where('store_id', $store->store_id)->where('type', 'StoreOnlineOrderTimingsDelivery')->get()->first();
                                          if($storeTimings) {
                                              $weekdays = maybe_decode($storeTimings->weekdays);
                                          } else {
                                              $weekdays = [];
                                          }  
                                          $cutOfTime = (isset($weekdays['cut_of_time'])?$weekdays['cut_of_time']:'15');
                                          if (isset($weekdays[$currentDay]['status']) && $weekdays[$currentDay]['status'] == 1) {
                                             $slotes = $weekdays[$currentDay];
                                             foreach (itemFor() as $itemFor) {
                                                if (isset($slotes[$itemFor]['open_time']) && !empty($slotes[$itemFor]['open_time']) && isset($slotes[$itemFor]['close_time']) && !empty($slotes[$itemFor]['close_time'])) {
                                                   $open_time = date('His', strtotime($slotes[$itemFor]['open_time']));
                                                   $close_time = date('His', strtotime($slotes[$itemFor]['close_time']));
                                                   if ($open_time < date('His', strtotime("+".$cutOfTime." minutes")) && $close_time > date('His', strtotime("+".$cutOfTime." minutes"))) {
                                                      $deliverySlot = date('His', strtotime("+".$cutOfTime." minutes"));
                                                   }
                                                }
                                             }
                                          }
                                       }
                                       echo $deliverySlot == true ?'This store is currently closed. Please select an another date time below.':'';                                       
                                       ?> 
                                       <label for="phone" <?php echo ($deliverySlot == true ? 'style="display:none;"':'') ?>>Sorry! this store is currently closed Pick a Date & Time for your future Order<br></label>
                                       <span class="deliveryButton" <?php echo ($deliverySlot == false ? 'style="display:none;"':'') ?>>
                                          <b>You can opt for Delivery order type<br>
                                          <!-- <a href="<?php echo url('/estore/items') ?>?order_type=Delivery&pickup_when=Now&suburb=<?php echo $store->store_city; ?>&pincode=<?php echo $store->store_postalCode; ?>&store=<?php echo str_replace(' ','-',$store->store_title.'-'.$store->store_id) ?>&order_date=<?php echo date('Ymd'); ?>&order_time=<?php echo date('His') ?>">Deliver Now</a> -->
                                          <a href="<?php echo url('/order/online') ?>">Deliver Now</a> </b> <br><br>  
                                          <label for="phone">OR</label><br>
                                       </span>
                                 </div>
                              </div>
                              <div class="col-md-6 animate-box">
                                 <div class="form-group">
                                    <label for="date">ORDER LATER DATE</label>
                                    <div class="form-field">
                                       <i class="icon icon-calendar2"></i>
                                       <select name="order_date" required="" class="form-control" id="order_date_select">
                                          <option value="">Select Date</option>
                                          <?php 
                                          $selectedAttr = 'selected';
                                          $selectedDate = '';
                                          foreach (getDateRange(20) as $date) {
                                             $day = date('D', strtotime($date));
                                             if (isset($weekdays[$day]['status']) && $weekdays[$day]['status'] == 1) {
                                                if (!$selectedDate) {
                                                   $selectedDate = $date;
                                                }
                                                echo '<option value="'.$date.'" data-day="'.$day.'" '.$selectedAttr.'>'.date('D d M Y', strtotime($date)).'</option>';
                                                $selectedAttr = '';
                                             }                                             
                                          }
                                          ?>                                          
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6 animate-box">
                                 <div class="form-group">
                                    <label for="time">ORDER LATER TIME</label>
                                    <div class="form-field">
                                       <i class="icon icon-arrow-down3"></i>
                                       <select name="order_time" required="" class="form-control" id="order_time_select">
                                          <option value="">Select Time</option>  
                                          <?php
                                          $todayTimings = $anotherTimings = '<option value="">Select Time</option>';
                                          if (isset($weekdays[$currentDay]['status']) && $weekdays[$currentDay]['status'] == 1) {
                                             $slotes = $weekdays[$currentDay];
                                             foreach (itemFor() as $itemFor) {
                                                if (isset($slotes[$itemFor]['open_time']) && !empty($slotes[$itemFor]['open_time']) && isset($slotes[$itemFor]['close_time']) && !empty($slotes[$itemFor]['close_time'])) {
                                                   $open_time = date('His', strtotime($slotes[$itemFor]['open_time']));
                                                   $close_time = date('His', strtotime($slotes[$itemFor]['close_time'])).'-';
                                                   $sloteMinute = $slotes['interval'];
                                                   while ($open_time < $close_time) {
                                                      if ($selectedDate == date('Ymd')) {
                                                          if ($open_time > date('His', strtotime("+".$sloteMinute." minutes"))) {
                                                              echo '<option value="'.$open_time.'">'.date('h:i A', strtotime($open_time)).'</option>';
                                                          } 
                                                      }else{
                                                          echo '<option value="'.$open_time.'">'.date('h:i A', strtotime($open_time)).'</option>';
                                                      } 
                                                       $open_time = date('His', strtotime($open_time)+($sloteMinute*60));
                                                   }
                                                }
                                             }
                                          }
                                          ?>                                          
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-12 animate-box">
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
      $('#order_date_select').change(function(event) {
         var selectedDate = $(this).val();
         var selectedDay = $(this).find(':selected').attr('data-day');
         $.ajax({
            url: '<?php echo url('estore/getSelectedTimes') ?>',
            type: 'GET',
            data: {
               selectedDate: selectedDate,
               selectedDay: selectedDay, 
               store_id: <?php echo $store->store_id ?>,
               orderType: 'StoreOnlineOrderTimingsPickup'
            },
         })
         .done(function(response) {
            if (response == 'DeliveryAvailable') {
               $('.deliveryButton').fadeIn();
               $('#order_time_select').html('<option value="">Select Time</option>');
            } else {
               $('.deliveryButton').fadeOut();
               $('#order_time_select').html(response);
            }            
         });
      });
   });
</script><?php /**PATH /var/www/html/maharaja-hotel/resources/views/Estore/PickupStoreTiming.blade.php ENDPATH**/ ?>
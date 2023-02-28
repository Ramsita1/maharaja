<div class="page-body">
   <h3>Vouchers</h3>
   <?php 
      if ($voucher->voucher_id) {
         echo Form::open(['route' => array('vouchers.update', $voucher->voucher_id), 'method' => 'put', 'class' => 'md-float-material']);
      }else{
         echo Form::open(['route' => array('vouchers.store'), 'method' => 'post', 'class' => 'md-float-material']);
      }    
      $currentUser = getCurrentUser();   
   ?>
      <div class="row">
         <div class="col-md-6">
            <div class="card">            
               <div class="card-block">         
                  <div class="">                          
                     <div class="input-group row">
                        <label class="col-form-label" for="code">Code</label><br>
                        <input type="text" name="code" required="" id="code" class="form-control form-control-lg" placeholder="Code" value="<?php echo $voucher->code ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="description">Description</label><br>
                        <textarea name="description" id="description" class="form-control form-control-lg" placeholder="Description"><?php echo $voucher->description ?></textarea>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="discount_type">Discount Type</label><br>
                        <select class="form-control" id="discount_type" name="discount_type">
                           <option value="Percentage" <?php echo ($voucher->discount_type == 'Percentage'?'selected':''); ?>>Pecentage %</option>
                           <option value="FlatAmount" <?php echo ($voucher->discount_type == 'FlatAmount'?'selected':''); ?>>Flat Amount</option>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="discount">Discount</label><br>
                        <input type="text" name="discount" id="discount" class="form-control form-control-lg InputNumber" placeholder="Discount" value="<?php echo $voucher->discount ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="max_discount">Max Discount</label><br>
                        <input type="text" name="max_discount" id="max_discount" class="form-control form-control-lg InputNumber" placeholder="Discount" value="<?php echo $voucher->max_discount ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="min_order">Min Order</label><br>
                        <input type="text" name="min_order" minlength="0" maxlength="100" id="min_order" class="form-control form-control-lg InputNumber" placeholder="Min Order" value="<?php echo $voucher->min_order ?>">
                        <span class="md-line"></span>
                     </div>
                     <?php 
                     if ($currentUser->role == 'Admin') {
                        ?>
                        <div class="input-group row">
                           <label class="col-form-label" for="store_id">Store</label>
                           <select class="form-control form-control-lg select2" id="store_id" name="store_id">
                              <option value="">Select</option>
                              <?php 
                              $store_id = $voucher->store_id;
                              $stores = \App\Stores::get();
                              foreach ($stores as $store) {
                                 echo '<option value="'.$store->store_id.'" '.($store->store_id == $voucher->store_id?'selected':'').'>'.$store->store_title.'</option>';
                              }
                              ?>
                           </select>
                           <span class="md-line"></span>
                        </div>
                        <?php
                     } else {
                        $store_id = $currentUser->store_id;
                        ?>
                        <input type="hidden" name="store_id" value="<?php echo $currentUser->store_id ?>">
                        <?php
                     }
                     ?> 
                     <div class="input-group row">
                        <label class="col-form-label" for="category_id">Except Item Category</label>
                        <select class="form-control form-control-lg select2" id="category_id" name="category_id">
                           <option value="">Select</option>
                           <?php 
                           $itemCategories = \App\MenuItemsCategory::where('store_id', $store_id)->get();
                           foreach ($itemCategories as $itemCategory) {
                              echo '<option value="'.$itemCategory->item_cat_id.'" '.($itemCategory->item_cat_id == $voucher->category_id?'selected':'').'>'.$itemCategory->cat_name.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="user_tags">For Special User</label>
                        <select class="form-control form-control-lg select2" id="user_tags" name="user_tags[]" multiple>
                           <option value="">Select</option>
                           <?php 
                           $users = \App\User::where('role', 'Customer')->get();
                           $user_tags = maybe_decode($voucher->user_tags);
                           $user_tags = (is_array($user_tags)?$user_tags:((array)$user_tags));
                           foreach ($users as $user) {
                              echo '<option value="'.$user->user_id.'" '.(in_array($user->user_id, $user_tags)?'selected':'').'>'.$user->name.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="location">For Special Location</label>
                        <select class="form-control form-control-lg select2" id="location" name="location[]" multiple>
                           <option value="">Select</option>
                           <?php 
                           $locations = \App\StoreDeliveryLocationPrice::where('store_id', $store_id)->get();
                           $vouch_location = maybe_decode($voucher->location);
                           $vouch_location = (is_array($vouch_location)?$vouch_location:((array)$vouch_location));
                           foreach ($locations as $location) {
                              echo '<option value="'.$location->store_delivery_location_id.'" '.(in_array($location->store_delivery_location_id, $vouch_location)?'selected':'').'>'.$location->postal_code.' - '.$location->city.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="start_date">Start Date</label><br>
                        <input type="text" name="start_date" id="start_date" class="form-control form-control-lg datePicker" placeholder="Expiry Date" value="<?php echo $voucher->start_date ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="start_time">Start Time</label><br>
                        <input type="text" name="start_time" id="start_time" class="form-control form-control-lg timepicker" placeholder="Start Time" value="<?php echo $voucher->start_time ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="expiry_date">Expiry Date</label><br>
                        <input type="text" name="expiry_date" id="expiry_date" class="form-control form-control-lg datePicker" placeholder="Expiry Date" value="<?php echo $voucher->expiry_date ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="expiry_time">Expiry Time</label><br>
                        <input type="text" name="expiry_time" id="expiry_time" class="form-control form-control-lg timepicker" placeholder="Expiry Time" value="<?php echo $voucher->expiry_time ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="usage_for">Voucher Usage For</label><br>
                        <select class="form-control select2" id="usage_for" name="usage_for">
                           <option value="Delivery" <?php echo ($voucher->usage_for == 'Delivery'?'selected':''); ?>>Only Delivery Order</option>
                           <option value="Pickup" <?php echo ($voucher->usage_for == 'Pickup'?'selected':''); ?>>Only Pickup Order</option>
                           <option value="Both" <?php echo ($voucher->usage_for == 'Both'?'selected':''); ?>>Both Orders</option>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="usage_many">How Many Time Usage</label><br>
                        <select class="form-control select2" id="usage_many" name="usage_many">
                           <option value="Single" <?php echo ($voucher->usage_many == 'Single'?'selected':''); ?>>Single</option>
                           <option value="Multiple" <?php echo ($voucher->usage_many == 'Multiple'?'selected':''); ?>>Multiple</option>
                           <option value="NoLimit" <?php echo ($voucher->usage_many == 'NoLimit'?'selected':''); ?>>No Limit</option>
                        </select>
                        <div class="usage_many_multiple_multiple" style="display:<?php echo $voucher->usage_many == 'Multiple' ? 'block' : 'none' ?>">
                           <label class="col-form-label" for="usage_many_multiple">How Many</label><br>
                           <input type="number" name="usage_many_multiple" value="<?php echo $voucher->usage_many_multiple ?>" id="usage_many_multiple" class="form-control InputNumber">
                        </div>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="week_of_day">Week of the day discount</label><br>
                        <?php 
                        $week_of_day = maybe_decode($voucher->week_of_day);
                        $week_of_day = (is_array($week_of_day)?$week_of_day:((array)$week_of_day));
                        $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
                        foreach ($days as $day) {
                           ?>
                           <div class="checkbox col-md-12">
                             <label>
                               <input type="checkbox" id="week_of_day_<?php echo $day ?>" name="week_of_day[]" <?php echo (in_array($day, $week_of_day)?'checked':'') ?> value="<?php echo $day ?>" data-toggle="toggle">
                               <?php echo $day ?>
                             </label>
                           </div>
                           <?php
                        }
                        ?>                        
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="free_delivery">Free Delivery</label><br>
                        <div class="checkbox col-md-12">
                          <label>
                            <input type="checkbox" id="free_delivery" name="free_delivery" <?php echo ($voucher->free_delivery == 1?'checked':'') ?> value="1" data-toggle="toggle">
                          </label>
                        </div>                 
                        <span class="md-line"></span>
                     </div>

                     <div class="input-group row">
                       <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Save</button>
                     </div>

                  </div>
               </div>
            </div>

         </div>     
      </div>   
   </form>
</div>
<div id="styleSelector">
</div>
<script type="text/javascript">
   jQuery(document).ready(function($) {
      $('#store_id').change(function(event) {
        var store_id = $(this).val();
        $.ajax({
           url: '<?php echo route('deals.getStoreCategory') ?>',
           type: 'GET',
           data: {store_id: store_id},
        })
        .done(function(response) {
         var result = $.parseJSON(response);
           $('#category_id').html(result.html);
           $('#menu_item_id').html(result.menuHtml);
           $('#location').html(result.locationHtml);
        });               
      }); 
      $('#usage_many').change(function(event) {
         $('#usage_many_multiple').val(0)
         if ($(this).val() == 'Multiple') {
            $('.usage_many_multiple_multiple').fadeIn();
         } else {
            $('.usage_many_multiple_multiple').fadeOut();
         }
      });
   });
</script><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Admin/Vouchers/CreateEdit.blade.php ENDPATH**/ ?>
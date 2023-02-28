<div class="page-body">
   <h3>Deals</h3>
   <?php 
      if ($deals->deal_id) {
         echo Form::open(['route' => array('deals.update', $deals->deal_id), 'method' => 'put', 'class' => 'md-float-material']);
      }else{
         echo Form::open(['route' => array('deals.store'), 'method' => 'post', 'class' => 'md-float-material']);
      }    
      $currentUser = getCurrentUser();
   ?>
      <div class="row">
         <div class="col-md-6">
            <div class="card">            
               <div class="card-block">         
                  <div class="">                          
                     <div class="input-group row">
                        <label class="col-form-label" for="deal_title">Deal Name</label><br>
                        <input type="text" name="deal_title" required="" id="deal_title" class="form-control form-control-lg" placeholder="Item Name" value="<?php echo $deals->deal_title ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="deal_description">Deal Description</label><br>
                        <textarea name="deal_description" id="deal_description" class="form-control form-control-lg" placeholder="Item Description"><?php echo $deals->deal_description ?></textarea>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="deal_type">Deal Type</label><br>
                        <select class="form-control" id="deal_type" name="deal_type">
                           <?php $dealTypes = [
                              '' => 'Select',
                              'FOD' => 'First Order Discount',
                              /*'LCAD' => 'Loyalty Credit Amount Discount',*/
                              'POD' => 'Pickup Order Discount',
                              'DOD' => 'Delivery Order Discount',
                              'FD' => 'Free Delivery',
                              'FI' => 'Free Item',
                              'BGF' => 'Buy & Get Free'
                           ]; 
                           foreach ($dealTypes as $typeKey => $typeValue) {
                              echo '<option value="'.$typeKey.'" '.($deals->deal_type == $typeKey?'selected':'') .'>'.$typeValue.'</option>';
                           }
                           ?>                           
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row FOD POD DOD FD HIDE">
                        <label class="col-form-label" for="discount">Discount (%)</label><br>
                        <input type="text" name="discount" minlength="0" maxlength="100" id="discount" class="form-control form-control-lg InputNumber" placeholder="Discount" value="<?php echo $deals->discount ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row FOD POD DOD FD FI HIDE">
                        <label class="col-form-label" for="min_order">Min Order</label><br>
                        <input type="text" name="min_order" minlength="0" maxlength="100" id="min_order" class="form-control form-control-lg InputNumber" placeholder="Min Order" value="<?php echo $deals->min_order ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row FOD POD DOD HIDE">
                        <label class="col-form-label" for="max_discount">Max Discount</label><br>
                        <input type="text" name="max_discount" minlength="0" maxlength="100" id="max_discount" class="form-control form-control-lg InputNumber" placeholder="Max Discount" value="<?php echo $deals->max_discount ?>">
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
                              $store_id = $deals->store_id;
                              $stores = \App\Stores::get();
                              foreach ($stores as $store) {
                                 echo '<option value="'.$store->store_id.'" '.($store->store_id == $deals->store_id?'selected':'').'>'.$store->store_title.'</option>';
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
                     <div class="input-group row FI HIDE">
                        <label class="col-form-label" for="menu_item_id">Menu Item</label>
                        <select class="form-control form-control-lg select2" id="menu_item_id" name="menu_item_id">
                           <option value="">Select</option>
                           <?php 
                           $menuItems = \App\MenuItems::where('store_id', $store_id)->get();
                           foreach ($menuItems as $menuItem) {
                              echo '<option value="'.$menuItem->menu_item_id.'" '.($menuItem->menu_item_id == $deals->menu_item_id?'selected':'').'>'.$menuItem->item_name.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row FOD POD DOD FD FI HIDE">
                        <label class="col-form-label" for="category_id">Except Item Category</label>
                        <select class="form-control form-control-lg select2" id="category_id" name="category_id">
                           <option value="">Select</option>
                           <?php 
                           $itemCategories = \App\MenuItemsCategory::where('store_id', $store_id)->get();
                           foreach ($itemCategories as $itemCategory) {
                              echo '<option value="'.$itemCategory->item_cat_id.'" '.($itemCategory->item_cat_id == $deals->category_id?'selected':'').'>'.$itemCategory->cat_name.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row FOD POD DOD FD FI BGF HIDE">
                        <label class="col-form-label" for="start_date">Start Date</label><br>
                        <input type="text" name="start_date" id="start_date" class="form-control form-control-lg datePicker" placeholder="Start Date" value="<?php echo $deals->start_date ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row FOD POD DOD FD FI BGF HIDE">
                        <label class="col-form-label" for="start_time">Start Time</label><br>
                        <input type="text" name="start_time" id="start_time" class="form-control form-control-lg timepicker" placeholder="Start Time" value="<?php echo $deals->start_time ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row FOD POD DOD FD FI BGF HIDE">
                        <label class="col-form-label" for="end_date">End Date</label><br>
                        <input type="text" name="end_date" id="end_date" class="form-control form-control-lg datePicker" placeholder="End Date" value="<?php echo $deals->end_date ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row FOD POD DOD FD FI BGF HIDE">
                        <label class="col-form-label" for="end_time">End Time</label><br>
                        <input type="text" name="end_time" id="end_time" class="form-control form-control-lg timepicker" placeholder="End Time" value="<?php echo $deals->end_time ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row FOD POD DOD FD FI BGF HIDE">
                        <label class="col-form-label" for="location">For Special Location</label>
                        <select class="form-control form-control-lg select2" id="location" name="location[]" multiple>
                           <option value="">Select</option>
                           <?php 
                           $locations = \App\StoreDeliveryLocationPrice::where('store_id', $store_id)->get();
                           $vouch_location = maybe_decode($deals->location);
                           $vouch_location = (is_array($vouch_location)?$vouch_location:((array)$vouch_location));
                           foreach ($locations as $location) {
                              echo '<option value="'.$location->store_delivery_location_id.'" '.(in_array($location->store_delivery_location_id, $vouch_location)?'selected':'').'>'.$location->postal_code.' - '.$location->city.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row FOD POD DOD FD FI BGF HIDE">
                        <label class="col-form-label" for="week_of_day">Week of the day discount</label><br>
                        <?php 
                        $week_of_day = maybe_decode($deals->week_of_day);
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
                        <div class="checkbox col-md-12">
                          <label>
                            <input type="checkbox" id="is_deal_auto_apply" name="is_deal_auto_apply" <?php echo ($deals->is_deal_auto_apply == 1?'checked':'') ?> value="1" data-toggle="toggle">
                            Is Deal Auto Apply
                          </label>
                        </div>
                     </div>                        
                     <div class="input-group row BGF HIDE">
                        <label class="col-form-label" for="buy_item">Buy Item</label><br>
                        <select class="form-control form-control-lg select2" id="buy_item" name="buy_item">
                           <option value="">Select</option>
                           <?php 
                           foreach ($menuItems as $menuItem) {
                              echo '<option value="'.$menuItem->menu_item_id.'" '.($menuItem->menu_item_id == $deals->buy_item?'selected':'').'>'.$menuItem->item_name.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row BGF HIDE">
                        <label class="col-form-label" for="buy_item_qnty">Buy Item Qty</label><br>
                        <input type="text" name="buy_item_qnty" minlength="0" maxlength="100" id="buy_item_qnty" class="form-control form-control-lg InputNumber" placeholder="Buy Item Qty" value="<?php echo $deals->buy_item_qnty ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row BGF HIDE">
                        <label class="col-form-label" for="get_item">Get Item</label><br>
                        <select class="form-control form-control-lg select2" id="get_item" name="get_item">
                           <option value="">Select</option>
                           <?php 
                           foreach ($menuItems as $menuItem) {
                              echo '<option value="'.$menuItem->menu_item_id.'" '.($menuItem->menu_item_id == $deals->get_item?'selected':'').'>'.$menuItem->item_name.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row BGF HIDE">
                        <label class="col-form-label" for="get_item_qnty">Get Item Qty</label><br>
                        <input type="text" name="get_item_qnty" minlength="0" maxlength="100" id="get_item_qnty" class="form-control form-control-lg InputNumber" placeholder="Get Item Qty" value="<?php echo $deals->get_item_qnty ?>">
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
           $('#buy_item').html(result.menuHtml);
           $('#get_item').html(result.menuHtml);
           $('#location').html(result.locationHtml);
        });               
      }); 
      $('#deal_type').change(function(event) {
         var deal_type = $(this).val();
         $('.HIDE').css('display', 'none');
         $('.'+deal_type).css('display', 'block');
      });
      $('#deal_type').trigger('change');
   });
</script><?php /**PATH C:\xampp\htdocs\pza\resources\views/Admin/Deals/CreateEdit.blade.php ENDPATH**/ ?>
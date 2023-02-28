<?php
if (!$store->store_id) {
   echo Form::open(['route' => array('stores.store'), 'method' => 'post', 'class' => 'md-float-material']);
}else{
   echo Form::open(['route' => array('stores.update', $store->store_id), 'method' => 'put', 'class' => 'md-float-material']);
}
?>
<div class="input-group row">
   <label class="col-form-label" for="store_title">Store Name</label><br>
   <input type="text" name="store_title" required="" id="store_title" class="form-control form-control-lg" placeholder="Store Name" value="<?php echo $store->store_title ?>">
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_content">Store Content</label><br>
   <textarea name="store_content" id="store_content" class="form-control form-control-lg ckeditor" placeholder="Store Content"><?php echo $store->store_content ?></textarea>
   <span class="md-line"></span>
</div>
<div class="row m-t-30">
   <div class="col-md-12 imageUploadGroup">
      <?php 
      if ($store->media) {
         ?>
         <img src="<?php echo publicPath().'/'.$store->media ?>" id="media-img" style="display:block;width: 100%;height: 200px;">
         <button type="button" data-eid="media" style="display:none;" class="btn btn-success setFeaturedImage">Select image</button>
         <button type="button" data-eid="media" style="display:block;" class="btn btn-warning removeFeaturedImage">Remove image</button>
         <?php
      }else{
         ?>
         <img src="" id="media-img" style="width: 100%;height: 200px;display: none;">
         <button type="button" data-eid="media" class="btn btn-success setFeaturedImage">Select image</button>
         <button type="button" data-eid="media" class="btn btn-warning removeFeaturedImage">Remove  image</button>
         <?php
      }
      ?>                        
      <input type="hidden" name="media" id="media" value="<?php echo $store->media; ?>">
   </div>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_address">Store Address</label><br>
   <input type="text" name="store_address" required="" id="store_address" class="form-control form-control-lg" placeholder="Store Address" value="<?php echo $store->store_address ?>">
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_country">Store Country</label><br>
   <select name="store_country" id="store_country" class="form-control form-control-lg">
      <option value="">Select</option>
      <?php 
      foreach (getCountry() as $country) {
         echo '<option value="'.$country->name.'" '.($store->store_country == $country->name?'selected':'').'>'.$country->name.'</option>';
      }
      ?>
   </select>
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_suburb">Store Suburb</label><br>
   <select name="store_suburb" id="store_suburb" class="form-control form-control-lg">
      <option value="">Select</option>
      <?php 
      foreach (getState($store->store_country) as $state) {
         echo '<option value="'.$state->name.'" '.($store->store_suburb == $state->name?'selected':'').'>'.$state->name.'</option>';
      }
      ?>
   </select>
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_city">Store City</label><br>
   <select name="store_city" id="store_city" class="form-control form-control-lg">
      <option value="">Select</option>
      <?php 
      foreach (getStateCity($store->store_suburb) as $city) {
         echo '<option value="'.$city->name.'" '.($store->store_city == $city->name?'selected':'').'>'.$city->name.'</option>';
      }
      ?>
   </select>
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_postalCode">Store Post Code</label><br>
   <input type="text" name="store_postalCode" id="store_postalCode" class="form-control form-control-lg InputNumber" placeholder="Store Pickup Min Order" value="<?php echo $store->store_postalCode ?>">
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_status">Store Status</label><br>
   <select name="store_status" id="store_status" class="form-control form-control-lg">
      <option value="open" <?php echo ($store->store_status == 'open'?'selected':'') ?>>Open</option>      
      <option value="close" <?php echo ($store->store_status == 'close'?'selected':'') ?>>Close</option>
   </select>
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="user_id">Store Default User</label><br>
   <select name="user_id" id="user_id" required="" class="form-control form-control-lg">
      <option value="0">Select</option>
      <?php 
      $users = \App\User::whereIn('role', ['StoreAdmin'])->get();
         foreach ($users as $user) {
            echo '<option value="'.$user->user_id.'" '.($store->user_id == $user->user_id?'selected':'').'>'.$user->name.'</option>';
         }
      ?>
   </select>
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_food_type">Store Food Type</label><br>
   <input type="text" name="store_food_type" id="store_food_type" class="form-control form-control-lg" placeholder="Store Food Type" value="<?php echo $store->store_food_type ?>">
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_location_phone">Store Location Phone</label><br>
   <input type="text" name="store_location_phone" id="store_location_phone" class="form-control form-control-lg InputNumber" placeholder="Store Location Phone" value="<?php echo $store->store_location_phone ?>">
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_location_email">Store Location Email</label><br>
   <input type="email" name="store_location_email" id="store_location_email" class="form-control form-control-lg" placeholder="Store Location Emai" value="<?php echo $store->store_location_email ?>">
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_menu_style">Store Menu Style</label><br>
   <input type="text" name="store_menu_style" id="store_menu_style" class="form-control form-control-lg" placeholder="Store Menu Style" value="<?php echo $store->store_menu_style ?>">
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_pickup_minOrder">Store Pickup Min Order</label><br>
   <input type="text" name="store_pickup_minOrder" id="store_pickup_minOrder" class="form-control form-control-lg InputNumber" placeholder="Store Pickup Min Order" value="<?php echo $store->store_pickup_minOrder ?>">
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_extra_charges">Store Minimum delivery extra charges</label><br>
   <div class="checkbox col-md-12">
     <label>
       <input type="checkbox" name="store_extra_charges" value="yes" <?php echo ($store->store_extra_charges == 'yes'?'checked':'') ?> data-toggle="toggle">
     </label>
   </div>
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_enable_tax">Enable Tax</label><br>
   <div class="checkbox col-md-12">
     <label>
       <input type="checkbox" id="store_enable_tax" name="store_enable_tax" value="yes" <?php echo ($store->store_enable_tax == 'yes'?'checked':'') ?> data-toggle="toggle">
     </label>
   </div>
   <div class="input-group row col-md-12">
      <label class="col-form-label" for="store_tax">Tax %</label><br>
      <input type="text" name="store_tax" id="store_tax" class="form-control form-control-lg InputNumber" placeholder="Tax %" value="<?php echo $store->store_tax ?>">
   </div>
   <span class="md-line"></span>
</div>

<div class="input-group row">
   <label class="col-form-label" for="store_enable_tip">Enable Tip for Delivery boy</label><br>
   <div class="checkbox col-md-12">
     <label>
       <input type="checkbox" id="store_enable_tip" name="store_enable_tip" value="yes" <?php echo ($store->store_enable_tip == 'yes'?'checked':'') ?> data-toggle="toggle">
     </label>
   </div>
   <div class="input-group row col-md-12">
      <label class="col-form-label" for="store_delivery_boy_tips">Tip eg{1,5,7}</label><br>
      <input type="text" name="store_delivery_boy_tips" id="store_delivery_boy_tips" class="form-control form-control-lg" placeholder="Tip eg{1,5,7}" value="<?php echo $store->store_delivery_boy_tips ?>">
   </div>
   <span class="md-line"></span>
</div>
<div class="input-group row">
   <label class="col-form-label" for="store_you_may_like_item_show_count">Store You May Like Item Show Count</label><br>
   <input type="text" name="store_you_may_like_item_show_count" id="store_you_may_like_item_show_count" class="form-control form-control-lg InputNumber" placeholder="Store You May Like Item Show Count" value="<?php echo $store->store_you_may_like_item_show_count ?>">
   <span class="md-line"></span>
</div>
<div class="row m-t-30 col-md-2">
  <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Save</button>
</div>
</form><?php /**PATH C:\xampp\htdocs\pza\resources\views/Admin/Store/StoreInfo.blade.php ENDPATH**/ ?>
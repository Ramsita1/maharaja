<div class="page-body">
   <?php 
      if ($menuItem->menu_item_id) {
         echo Form::open(['route' => array('menuItem.update', $menuItem->menu_item_id), 'method' => 'put', 'class' => 'md-float-material']);
      }else{
         echo Form::open(['route' => array('menuItem.store'), 'method' => 'post', 'class' => 'md-float-material']);
      }       
   ?>
      <div class="row">
         <div class="col-md-1"></div>
         <div class="col-md-10">
            <div class="card">            
               <div class="card-block"> 
                  <h3>Menu Item</h3>        
                  <div class="">                          
                     <div class="input-group row">
                        <label class="col-form-label" for="item_name">Item Name</label><br>
                        <input type="text" name="item_name" required="" id="item_name" class="form-control form-control-lg" placeholder="Item Name" value="<?php echo $menuItem->item_name ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="item_description">Item Description</label><br>
                        <textarea name="item_description" id="item_description" class="form-control form-control-lg" placeholder="Item Description"><?php echo $menuItem->item_description ?></textarea>
                        <span class="md-line"></span>
                     </div>
                     <div class="row m-t-30 input-group">
                        <label class="col-form-label" for="item_description">Display Image</label><br>
                        <div class="col-md-12 imageUploadGroup">
                           <?php 
                           if ($menuItem->item_image) {
                              ?>
                              <img src="<?php echo publicPath().'/'.$menuItem->item_image ?>" id="item_image-img" style="display:block;width: 100%;height: 200px;">
                              <button type="button" data-eid="item_image" style="display:none;" class="btn btn-success setFeaturedImage">Select image</button>
                              <button type="button" data-eid="item_image" style="display:block;" class="btn btn-warning removeFeaturedImage">Remove image</button>
                              <?php
                           }else{
                              ?>
                              <img src="" id="item_image-img" style="width: 100%;height: 200px;display: none;">
                              <button type="button" data-eid="item_image" class="btn btn-success setFeaturedImage">Select image</button>
                              <button type="button" data-eid="item_image" class="btn btn-warning removeFeaturedImage">Remove  image</button>
                              <?php
                           }
                           ?>                        
                           <input type="hidden" name="item_image" id="item_image" value="<?php echo $menuItem->item_image; ?>">
                        </div>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="item_is">Item Is</label><br>
                        <select name="item_is" required="" id="item_is" class="form-control form-control-lg">
                           <option value="Simple" <?php echo ($menuItem->item_is == 'Simple'?'selected':'')?>>Simple</option>
                           <option value="Attributes" <?php echo ($menuItem->item_is == 'Attributes'?'selected':'')?>>Attributes</option>
                        </select>
                     </div>
                     <div class="itemSimple">
                        <div class="input-group row">
                           <label class="col-form-label" for="item_price">Item Price</label><br>
                           <input type="text" name="item_price" required="" id="item_price" class="form-control form-control-lg InputNumber" placeholder="Item Price" value="<?php echo $menuItem->item_price ?>">
                           <span class="md-line"></span>
                        </div>
                        <div class="input-group row">
                           <label class="col-form-label" for="item_sale_price">Item Sale Price</label><br>
                           <input type="text" name="item_sale_price" id="item_sale_price" class="form-control form-control-lg InputNumber" placeholder="Item Sale Price" value="<?php echo $menuItem->item_sale_price ?>">
                           <span class="md-line"></span>
                        </div>
                        <div class="input-group row">
                           <label class="col-form-label" for="item_discount">Discount %</label><br>
                           <input type="text" name="item_discount" minlength="0" maxlength="100" id="item_discount" class="form-control form-control-lg InputNumber" placeholder="Discount" value="<?php echo $menuItem->item_discount ?>">
                           <span class="md-line"></span>
                        </div>
                        <div class="input-group row">
                           <label class="col-form-label" for="item_discount_start">Discount Start</label><br>
                           <input type="text" name="item_discount_start" id="item_discount_start" class="form-control form-control-lg datePicker" placeholder="Discount Start" value="<?php echo $menuItem->item_discount_start ?>">
                           <span class="md-line"></span>
                        </div>
                        <div class="input-group row">
                           <label class="col-form-label" for="item_discount_end">Discount End</label><br>
                           <input type="text" name="item_discount_end" id="item_discount_end" class="form-control form-control-lg datePicker" placeholder="Discount End" value="<?php echo $menuItem->item_discount_end ?>">
                           <span class="md-line"></span>
                        </div>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="item_category">Item Category</label>
                        <select class="form-control form-control-lg" id="item_category" name="item_category">
                           <option value="">Select</option>
                           <?php 
                           $store_id = Auth::user()->store_id;
                           $itemCategories = \App\MenuItemsCategory::where('store_id', $store_id)->get();
                           foreach ($itemCategories as $itemCategory) {
                              echo '<option value="'.$itemCategory->item_cat_id.'" '.($itemCategory->item_cat_id == $menuItem->item_category?'selected':'').'>'.$itemCategory->cat_name.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="item_display_in">Item Display In</label>
                        <select class="form-control form-control-lg" id="item_display_in" name="item_display_in">
                           <?php 
                           foreach (itemDisplayIn() as $itemDisplayIn) {
                              echo '<option value="'.$itemDisplayIn.'" '.($itemDisplayIn == $menuItem->item_display_in?'selected':'').'>'.$itemDisplayIn.'</option>';
                           } ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="item_for">Item For</label>
                        <div class="col-md-12 row">
                           <div class="col-md-12">
                              <label for="Item_for_all">
                                 <b>All</b>&nbsp;
                                 <input type="checkbox" id="Item_for_all" value="1">
                              </label>
                           </div>
                           <?php                            
                           foreach (itemFor() as $itemFor) {
                              $itemForData = (array)(isset($menuItem->item_for->$itemFor)?$menuItem->item_for->$itemFor:'');
                              ?>
                              <div class="input-group">
                                 <div class="col-md-4">
                                    <label for="Item_for_<?php echo $itemFor; ?>">
                                       <b><?php echo $itemFor; ?></b>&nbsp;
                                       <input type="checkbox" class="checkAllCheckbox" data-showItems="show_<?php echo $itemFor; ?>" name="item_for[<?php echo $itemFor; ?>][status]" <?php echo (isset($itemForData['status']) && $itemForData['status'] == 1?'checked':'') ?> id="Item_for_<?php echo $itemFor; ?>" value="1">
                                    </label>
                                 </div>
                                 <div class="col-md-4 show_<?php echo $itemFor; ?>">
                                    <label class="col-form-label" for="pickup_price_<?php echo $itemFor; ?>">Pickup Price</label>
                                    <input type="text" class="form-control InputNumber" name="item_for[<?php echo $itemFor; ?>][pickup_price]" id="pickup_price_<?php echo $itemFor; ?>" value="<?php echo (isset($itemForData['pickup_price'])?$itemForData['pickup_price']:'') ?>">
                                 </div>
                                 <div class="col-md-4 show_<?php echo $itemFor; ?>">
                                    <label class="col-form-label" for="delivery_price_<?php echo $itemFor; ?>">Delivery Price</label>
                                    <input type="text" class="form-control InputNumber" name="item_for[<?php echo $itemFor; ?>][delivery_price]" id="delivery_price_<?php echo $itemFor; ?>" value="<?php echo (isset($itemForData['delivery_price'])?$itemForData['delivery_price']:'') ?>">
                                 </div>
                              </div>
                              <br>
                              <?php
                           } ?>
                           
                        </div>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="item_status">Status</label>
                        <select class="form-control form-control-lg" id="item_status" name="item_status">
                           <option value="Active" <?php echo ('Active' == $menuItem->item_status?'selected':'') ?>>Active</option>
                           <option value="Inactive" <?php echo ('Inactive' == $menuItem->item_status?'selected':'') ?>>Inactive</option>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="show_at_home">Show At Home</label>
                        <select class="form-control form-control-lg" id="show_at_home" name="show_at_home">
                           <option value="Yes" <?php echo ('Yes' == $menuItem->show_at_home?'selected':'') ?>>Yes</option>
                           <option value="No" <?php echo ('No' == $menuItem->show_at_home?'selected':'') ?>>No</option>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="is_delicous">Is Delicous</label>
                        <select class="form-control form-control-lg" id="is_delicous" name="is_delicous">
                           <?php 
                           foreach (isDelicous() as $isDelicous) {
                              echo '<option value="'.$isDelicous.'" '.($isDelicous == $menuItem->is_delicous?'selected':'').'>'.$isDelicous.'</option>';
                           } ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="is_non_discountAble">Is Non DiscountAble</label><br>
                        <div class="checkbox col-md-12">
                          <label>
                            <input type="checkbox" id="is_non_discountAble" name="is_non_discountAble" value="1" <?php echo ($menuItem->is_non_discountAble == 1?'checked':'') ?> data-toggle="toggle">
                          </label>
                        </div>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="is_you_may_like">Is You Make Like Item</label><br>
                        <div class="checkbox col-md-12">
                          <label>
                            <input type="checkbox" id="is_you_may_like" name="is_you_may_like" value="Yes" <?php echo ($menuItem->is_you_may_like == 'Yes'?'checked':'') ?> data-toggle="toggle">
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
      $('.checkAllCheckbox').change(function(event) {
         var showItems = $(this).attr('data-showItems');
         if ($(this).is(':checked')) {
            $('.'+showItems).fadeIn();
         } else {
            $('.'+showItems).fadeOut();
         }
      });
      $('#Item_for_all').change(function(event) {
         if ($(this).is(':checked')) {
            $('.checkAllCheckbox').prop('checked', true);
            $.each($('.checkAllCheckbox'), function(index, val) {
               var showItems = $(this).attr('data-showItems');
               if ($(this).is(':checked')) {
                  $('.'+showItems).fadeIn();
               } else {
                  $('.'+showItems).fadeOut();
               }
            });
         } else {
            $('.checkAllCheckbox').prop('checked', false);
            $.each($('.checkAllCheckbox'), function(index, val) {
               var showItems = $(this).attr('data-showItems');
               if ($(this).is(':checked')) {
                  $('.'+showItems).fadeIn();
               } else {
                  $('.'+showItems).fadeOut();
               }
            });
         }         
      });
      $('.checkAllCheckbox').trigger('change');
      $('.addAttributeButton').click(function(event) {
         
         var count = $('.itemParent:last-child').attr('data-attr_count');
         count++;
         $.ajax({
            url: '<?php echo route('getItemAttribute') ?>',
            type: 'GET',
            data: {count: count},
         })
         .done(function(response) {
            $('.itemAttributes').append(response);
         });         
      });
      $(document).on('click', '.removeAttributeButton', function(event) {
         event.preventDefault();
         $(this).closest('.itemParent').remove();
      });
   });
</script><?php /**PATH /home/k9c9adh99pg7/public_html/pza/resources/views/Admin/MenuItems/CreateEdit.blade.php ENDPATH**/ ?>
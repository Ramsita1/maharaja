<div class="page-body">
   <?php 
      if ($menuItemBanner->banner_id) {
         echo Form::open(['route' => array('menuItemBanner.update', $menuItemBanner->banner_id), 'method' => 'put', 'class' => 'md-float-material']);
      }else{
         echo Form::open(['route' => array('menuItemBanner.store'), 'method' => 'post', 'class' => 'md-float-material']);
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
                        <label class="col-form-label" for="banner_name">Banner Name</label><br>
                        <input type="text" name="banner_name" required="" id="banner_name" class="form-control form-control-lg" placeholder="Item Name" value="<?php echo $menuItemBanner->banner_name ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="row m-t-30 input-group">
                        <label class="col-form-label" for="item_description">Display Image</label><br>
                        <div class="col-md-12 imageUploadGroup">
                           <?php 
                           if ($menuItemBanner->banner_image) {
                              ?>
                              <img src="<?php echo publicPath().'/'.$menuItemBanner->banner_image ?>" id="banner_image-img" style="display:block;width: 100%;height: 200px;">
                              <button type="button" data-eid="banner_image" style="display:none;" class="btn btn-success setFeaturedImage">Select image</button>
                              <button type="button" data-eid="banner_image" style="display:block;" class="btn btn-warning removeFeaturedImage">Remove image</button>
                              <?php
                           }else{
                              ?>
                              <img src="" id="banner_image-img" style="width: 100%;height: 200px;display: none;">
                              <button type="button" data-eid="banner_image" class="btn btn-success setFeaturedImage">Select image</button>
                              <button type="button" data-eid="banner_image" class="btn btn-warning removeFeaturedImage">Remove  image</button>
                              <?php
                           }
                           ?>                        
                           <input type="hidden" name="banner_image" id="banner_image" value="<?php echo $menuItemBanner->banner_image; ?>">
                        </div>
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
</div><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Admin/MenuItemBanners/CreateEdit.blade.php ENDPATH**/ ?>
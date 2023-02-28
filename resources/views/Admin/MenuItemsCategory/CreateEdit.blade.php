<div class="page-body">
   <h3>Menu Item Category</h3>
   <?php 
      if ($menuItemCategory->item_cat_id) {
         echo Form::open(['route' => array('menuItemCategory.update', $menuItemCategory->item_cat_id), 'method' => 'put', 'class' => 'md-float-material']);
      }else{
         echo Form::open(['route' => array('menuItemCategory.store'), 'method' => 'post', 'class' => 'md-float-material']);
      }       
   ?>
      <div class="row">
         <div class="col-md-6">
            <div class="card">            
               <div class="card-block">         
                  <div class="">                          
                     <div class="input-group row">
                        <label class="col-form-label" for="cat_name">Category Name</label><br>
                        <input type="text" name="cat_name" required="" id="cat_name" class="form-control form-control-lg" placeholder="Category Name" value="<?php echo $menuItemCategory->cat_name ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="cat_description">Category Description</label><br>
                        <textarea name="cat_description" id="cat_description" class="form-control form-control-lg" placeholder="Category Description"><?php echo $menuItemCategory->cat_description ?></textarea>
                        <span class="md-line"></span>
                     </div>
                     <div class="row m-t-30 input-group">
                        <div class="col-md-12 imageUploadGroup">
                           <?php 
                           if ($menuItemCategory->cat_image) {
                              ?>
                              <img src="<?php echo publicPath().'/'.$menuItemCategory->cat_image ?>" id="cat_image-img" style="display:block;width: 100%;height: 200px;">
                              <button type="button" data-eid="cat_image" style="display:none;" class="btn btn-success setFeaturedImage">Select image</button>
                              <button type="button" data-eid="cat_image" style="display:block;" class="btn btn-warning removeFeaturedImage">Remove image</button>
                              <?php
                           }else{
                              ?>
                              <img src="" id="cat_image-img" style="width: 100%;height: 200px;display: none;">
                              <button type="button" data-eid="cat_image" class="btn btn-success setFeaturedImage">Select image</button>
                              <button type="button" data-eid="cat_image" class="btn btn-warning removeFeaturedImage">Remove  image</button>
                              <?php
                           }
                           ?>                        
                           <input type="hidden" name="cat_image" id="cat_image" value="<?php echo $menuItemCategory->cat_image; ?>">
                        </div>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="cat_status">Status</label>
                        <select class="form-control form-control-lg" id="cat_status" name="cat_status">
                           <option value="Active" <?php echo ('Active' == $menuItemCategory->cat_status?'selected':'') ?>>Active</option>
                           <option value="Inactive" <?php echo ('Inactive' == $menuItemCategory->cat_status?'selected':'') ?>>Inactive</option>
                        </select>
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
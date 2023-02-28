<div class="page-body">
   <h3>Create Menu Item Type</h3>
   <?php 
      if ($menuItemType->item_type_id) {
         echo Form::open(['route' => array('menuItemType.update', $menuItemType->item_type_id), 'method' => 'put', 'class' => 'md-float-material']);
      }else{
         echo Form::open(['route' => array('menuItemType.store'), 'method' => 'post', 'class' => 'md-float-material']);
      }       
   ?>
      <div class="row">
         <div class="col-md-6">
            <div class="card">            
               <div class="card-block">         
                  <div class="">                          
                     <div class="input-group row">
                        <label class="col-form-label" for="type_name">Item Type</label><br>
                        <input type="text" name="type_name" required="" id="type_name" class="form-control form-control-lg" placeholder="Item Type" value="<?php echo $menuItemType->type_name ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="type_description">Type Description</label><br>
                        <textarea name="type_description" id="type_description" class="form-control form-control-lg" placeholder="Type Description"><?php echo $menuItemType->type_description ?></textarea>
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
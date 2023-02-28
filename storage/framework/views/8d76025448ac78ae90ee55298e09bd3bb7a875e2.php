<div class="page-body">
   <h3>Menu Attribute Size</h3>
   <?php 
      if ($menuAttributeSize->attribute_size_id) {
         echo Form::open(['route' => array('menuAttributeSize.update', $menuAttributeSize->attribute_size_id), 'method' => 'put', 'class' => 'md-float-material']);
      }else{
         echo Form::open(['route' => array('menuAttributeSize.store'), 'method' => 'post', 'class' => 'md-float-material']);
      }       
   ?>
      <div class="row">
         <div class="col-md-6">
            <div class="card">            
               <div class="card-block">         
                  <div class="">                          
                     <div class="input-group row">
                        <label class="col-form-label" for="size_name">Size</label><br>
                        <input type="text" name="size_name" required="" id="size_name" class="form-control form-control-lg" placeholder="Size" value="<?php echo $menuAttributeSize->size_name ?>">
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
</div><?php /**PATH /home/k9c9adh99pg7/public_html/pza/resources/views/Admin/MenuAttributeSize/CreateEdit.blade.php ENDPATH**/ ?>
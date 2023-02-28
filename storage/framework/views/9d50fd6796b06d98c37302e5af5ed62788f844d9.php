<div class="page-body">
   <style type="text/css">
      .attr_selection_mutli_value{
         display: none;
      }
   </style>
   <h3>Menu Attribute</h3>
   <?php 
      if ($menuAttribute->menu_attr_id) {
         echo Form::open(['route' => array('menuAttribute.update', $menuAttribute->menu_attr_id), 'method' => 'put', 'class' => 'md-float-material']);
      }else{
         echo Form::open(['route' => array('menuAttribute.store'), 'method' => 'post', 'class' => 'md-float-material']);
      }       
   ?>
      <div class="row">
         <div class="col-md-6">
            <div class="card">            
               <div class="card-block">         
                  <div class="">                          
                     <div class="input-group row">
                        <label class="col-form-label" for="attr_name">Attribute Name</label><br>
                        <input type="text" name="attr_name" required="" id="attr_name" class="form-control form-control-lg" placeholder="Attribute Name" value="<?php echo $menuAttribute->attr_name ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="attr_status">Attribute Status</label><br>
                        <select name="attr_status" class="form-control">
                           <option value="">Select</option>
                           <option value="Active" <?php echo ($menuAttribute->attr_status == 'Active'?'selected':'') ?>>Active</option>
                           <option value="Inactive" <?php echo ($menuAttribute->attr_status == 'Inactive'?'selected
                           ':'') ?>>Inactive</option>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="attr_selection">Attribute Selection</label><br><br>
                        <div class="input-group">
                           <label>Multiple&nbsp;<input type="radio" class="attr_selection" name="attr_selection" value="multiple" <?php echo ($menuAttribute->attr_selection == 'multiple'?'checked':'') ?>></label>&nbsp;&nbsp;&nbsp;&nbsp;
                           <label>Single&nbsp;<input type="radio" class="attr_selection" name="attr_selection" value="single" <?php echo ($menuAttribute->attr_selection == 'single'?'checked':'') ?>></label>
                        </div>
                        <div class="input-group row col-md-6 attr_selection_mutli_value">
                           <label class="col-form-label" for="attr_selection_mutli_value_min"> Limit Min choices </label><br>
                           <input type="number" name="attr_selection_mutli_value_min" id="attr_selection_mutli_value_min" class="form-control form-control-lg" placeholder=" Limit how many choices " value="<?php echo $menuAttribute->attr_selection_mutli_value_min ?>">
                           <br>
                           <label class="col-form-label" for="attr_selection_mutli_value_max"> Limit Max choices </label><br>
                           <input type="number" name="attr_selection_mutli_value_max" id="attr_selection_mutli_value_max" class="form-control form-control-lg" placeholder=" Limit how many choices " value="<?php echo $menuAttribute->attr_selection_mutli_value_max ?>">
                        </div>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="attr_status">Attribute Type</label><br><br>
                        <div class="input-group">
                           <label>Addition&nbsp;<input type="radio" name="attr_type" value="add" <?php echo ($menuAttribute->attr_type == 'add'?'checked':'') ?>></label>&nbsp;&nbsp;&nbsp;&nbsp;
                           <label>Remove&nbsp;<input type="radio" name="attr_type" value="remove" <?php echo ($menuAttribute->attr_type == 'remove'?'checked':'') ?>></label>
                        </div>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="attr_main_choice">Set as Top Position</label><br>
                        <div class="checkbox col-md-12">
                          <label>
                            <input type="checkbox" id="attr_main_choice" name="attr_main_choice" value="1" <?php echo ($menuAttribute->attr_main_choice == 1?'checked':'') ?> data-toggle="toggle">
                          </label>
                        </div>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="attr_mandatory">Set as Mandatory</label><br>
                        <div class="checkbox col-md-12">
                          <label>
                            <input type="checkbox" id="attr_mandatory" name="attr_mandatory" value="1" <?php echo ($menuAttribute->attr_mandatory == 1?'checked':'') ?> data-toggle="toggle">
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
      $('.attr_selection').change(function(event) {
         var value = $('.attr_selection:checked').val();
         if (value == 'multiple') {
            $('.attr_selection_mutli_value').fadeIn();
            $('#attr_selection_mutli_value').prop('required', true);
         } else {
            $('.attr_selection_mutli_value').fadeOut();
            $('#attr_selection_mutli_value').prop('required', false);
         }
      });
      $('.attr_selection').trigger('change');
   });
</script><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Admin/MenuAttributes/CreateEdit.blade.php ENDPATH**/ ?>
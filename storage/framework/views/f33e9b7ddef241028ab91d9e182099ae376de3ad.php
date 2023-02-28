<style type="text/css">
   .ui-widget.ui-widget-content{
      z-index: 99999999999;
   }
</style>
<div class="page-body">
   <div class="row">
      <div class="col-md-4 col-xl-4">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-12">
                        <h5 class="m-b-10">Add Edit Attribute</h5>
                     </div>
                     <?php 
                        if ($attribute->item_attr_id) {
                           echo Form::open(['route' => array('menuItemAttributes.update', $attribute->item_attr_id), 'method' => 'put', 'class' => 'md-float-material']);
                        }else{
                           echo Form::open(['route' => array('menuItemAttributes.store'), 'method' => 'post', 'class' => 'md-float-material']);
                        }       
                     ?>
                     <input type="hidden" name="menu_item_id" value="<?php echo Request()->get('menu_item_id') ?>">
                        <div class="">    
                           <div class="input-group row">
                              <label class="col-form-label">Search Exists Attributes</label><br>
                              <input type="text" class="form-control form-control-lg search-attribute ">
                              <span class="md-line"></span>
                           </div>  
                           <div class="input-group row">
                              <label class="col-form-label"><b>Or Enter new</b></label><br>  
                              <span class="md-line"></span>
                           </div>              
                           <div class="input-group row">
                              <label class="col-form-label" for="attr_name">Attribute Name</label><br>
                              <input type="text" name="attr_name" required="" id="attr_name" class="form-control form-control-lg" placeholder="Attribute Name" value="<?php echo $attribute->attr_name ?>">
                              <span class="md-line"></span>
                           </div>
                           <div class="input-group row">
                              <label class="col-form-label" for="attr_price">Attribute Price</label><br>
                              <input type="text" name="attr_price" required="" id="attr_price" class="form-control form-control-lg InputNumber" placeholder="Attribute Name" value="<?php echo $attribute->attr_price ?>">
                              <span class="md-line"></span>
                           </div>
                           <div class="input-group row">
                              <label class="col-form-label" for="attr_desc">Attribute Description</label><br>
                              <textarea name="attr_desc" required="" id="attr_desc" class="form-control form-control-lg" placeholder="Attribute Description"><?php echo $attribute->attr_desc ?></textarea>
                              <span class="md-line"></span>
                           </div>
                           <div class="input-group row">
                              <label class="col-form-label" for="attr_size">Attribute Size</label><br>
                              <select name="attr_size" id="attr_size" class="form-control">
                                 <option value="">Select</option>
                                 <?php 
                                 foreach ($menuAttributeSizes as $menuAttributeSize) {
                                    ?>
                                    <option value="<?php echo $menuAttributeSize->size_name ?>" <?php echo ($attribute->attr_size == $menuAttributeSize->size_name?'selected':'') ?>><?php echo $menuAttributeSize->size_name ?></option>
                                    <?php 
                                 }
                                 ?>                                 
                              </select>
                              <span class="md-line"></span>
                           </div>
                           <div class="input-group row">
                              <label class="col-form-label" for="menu_attr_id">Attribute Options</label><br>
                              <select name="menu_attr_id" id="menu_attr_id" class="form-control">
                                 <option value="">Select</option>
                                 <?php 
                                 foreach ($menuAttributes as $menuAttribute) {
                                    ?>
                                    <option value="<?php echo $menuAttribute->menu_attr_id ?>" <?php echo ($attribute->menu_attr_id == $menuAttribute->menu_attr_id?'selected':'') ?>><?php echo $menuAttribute->attr_name ?></option>
                                    <?php
                                 }
                                 ?>
                                 
                              </select>
                              <span class="md-line"></span>
                           </div>
                           <div class="input-group row">
                              <label class="col-form-label" for="attr_status">Attribute Status</label><br>
                              <select name="attr_status" id="attr_status" class="form-control">
                                 <option value="">Select</option>
                                 <option value="Active" <?php echo ($attribute->attr_status == 'Active'?'selected':'') ?>>Active</option>
                                 <option value="Inactive" <?php echo ($attribute->attr_status == 'Inactive'?'selected
                                 ':'') ?>>Inactive</option>
                              </select>
                              <span class="md-line"></span>
                           </div>      
                           <div class="input-group row">
                              <label class="col-form-label" for="attr_default_choice">Default Choice</label><br>
                              <div class="checkbox col-md-12">
                                <label>
                                  <input type="checkbox" id="attr_default_choice" name="attr_default_choice" value="1" <?php echo ($attribute->attr_default_choice == 1?'checked':'') ?> data-toggle="toggle">
                                </label>
                              </div>
                              <span class="md-line"></span>
                           </div>                     
                           <div class="input-group row">
                             <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Save</button>
                           </div>

                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-md-8 col-xl-8">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-5">
                        <h5 class="m-b-10">Menu Item Attributes</h5>
                        <h6 class="m-b-10"><b>Dish Name:-</b><i style="color: red;"> <?php echo \App\MenuItems::where('menu_item_id', Request()->get('menu_item_id'))->get()->pluck('item_name')->first(); ?></i></h6>
                     </div>
                     <div class="col-md-7">
                        <p>Search attribute For existing items. <button class="btn btn-info" data-toggle="modal" data-target="#searchItemAttribbute">Search</button></p>                        
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Attribute Name</th>
                              <th>Attribute Price</th>
                              <th>Attribute Type</th>
                              <th>Size</th>
                              <th>Status</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           foreach ($menuItemAttributes as $menuItemAttribute) {
                              ?>
                              <tr>
                                 <td><?php echo $menuItemAttribute->attr_name ?></td>
                                 <td><?php echo priceFormat($menuItemAttribute->attr_price) ?></td>
                                 <td><?php echo $menuItemAttribute->attribute ?></td>
                                 <td><?php echo $menuItemAttribute->attr_size ?></td>
                                 <td><?php echo $menuItemAttribute->attr_status ?></td>
                                 <td>
                                    <a href="<?php echo route('menuItemAttributes.index') ?>?item_attr_id=<?php echo $menuItemAttribute->item_attr_id ?>&menu_item_id=<?php echo $menuItemAttribute->menu_item_id ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> 
                                    | 
                                    <?php echo Form::open(['route' => array('menuItemAttributes.destroy', $menuItemAttribute->item_attr_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
                                       <button type="submit" class="btn btn-danger"><span class="pcoded-micon"><i class="ti-trash"></i></span></button>
                                    </form>                                
                                 </td>
                              </tr>
                              <?php
                           }
                           ?>                           
                        </tbody>
                     </table>
                  </div>
                  <?php echo $menuItemAttributes->links(); ?>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div>
<script type="text/javascript">
   jQuery(document).ready(function($) {
      $('#attr_default_choice').change(function(event) {
         if ($(this).is(':checked')) {
            $('#attr_price').val(0).prop('readonly', true);
         }
      });
      $('#attr_default_choice').trigger('change');
      $('.search-attribute').autocomplete({
         source: '<?php echo route('menuItemAttributes.searchattribute') ?>',
         dataType: 'json',
         select: function (event, ui ) {
            var attribute = ui.item.data;
            $('#attr_name').val(attribute.attr_name);
            $('#attr_desc').val(attribute.attr_desc);
            $('#attr_price').val(attribute.attr_price);
            $('#attr_size').val(attribute.attr_size);
            $('#attr_status').val(attribute.attr_status);
            $('#menu_attr_id').val(attribute.menu_attr_id);
            if (attribute.attr_default_choice == 1) {
               $('#attr_default_choice').prop('checked', true);
            }
         }
      });
      $('.search-menu-items').autocomplete({
         source: '<?php echo route('menuItemAttributes.searchMenuItem') ?>?menu_item_id=<?php echo Request()->get('menu_item_id') ?>',
         dataType: 'json',
         select: function (event, ui ) {
            var menu_item_id = ui.item.id;
            $.ajax({
               url: '<?php echo route('menuItemAttributes.getItemAttributes') ?>',
               type: 'GET',
               data: {menu_item_id: menu_item_id},
            })
            .done(function(responsive) {
               $('.selectAttributes').html(responsive)
            });            
         }
      });
      $(document).on('click', '.copyAttribute', function(event) {
         event.preventDefault();
         if ($('.item_attr_ids').length == 0) {
            window.alert('Please search item to copy.');
            return false;
         }
         var checkedAttribute = $('.item_attr_ids:checked');
         if (checkedAttribute.length == 0) {
            window.alert('Please select attribute.');
            return false;
         }
         var item_attr_ids = [];
         $.each(checkedAttribute, function(index, val) {
            var item_attr_id = $(this).val();
            item_attr_ids.push(item_attr_id);
         });
         $.ajax({
            url: '<?php echo route('menuItemAttributes.copyItemAttributes') ?>',
            type: 'GET',
            data: {
               item_attr_ids: item_attr_ids,
               menu_item_id: '<?php echo Request()->get('menu_item_id') ?>'
            },
         })
         .done(function(response) {
            if (response == 'success') {
               window.alert('Attribites copied successfully');
               window.location.reload();
            }
         });         
      });
   });
</script>

<div id="searchItemAttribbute" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Search Item Attribute</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Please enter item name to search.</p>
        <input type="text" name="" class="form-control search-menu-items">
        <ul class="selectAttributes list-group">
           
        </ul>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-info copyAttribute">Copy</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div><?php /**PATH /home/k9c9adh99pg7/public_html/pza/resources/views/Admin/MenuItems/ManageAttributes.blade.php ENDPATH**/ ?>
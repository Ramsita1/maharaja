<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-6">
                        <h5 class="m-b-10">Menu Item Category</h5>
                     </div>
                     <div class="col-md-6">
                        <a href="<?php echo route('menuItemCategory.create') ?>" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Category Name</th>
                              <th>Category Description</th>
                              <th>Created Date</th>
                              <th>Last Update Date</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody id="sortable">
                           <?php 
                           foreach ($menuItemcategories as $menuItemCategory) {
                              ?>
                              <tr class="tr_rows" data-item_cat_id="<?php echo $menuItemCategory->item_cat_id; ?>">
                                 <td><?php echo $menuItemCategory->cat_name ?></td>
                                 <td><?php echo $menuItemCategory->cat_description ?></td>
                                 <td><?php echo dateFormat($menuItemCategory->created_at); ?></td>
                                 <td><?php echo dateFormat($menuItemCategory->updated_at); ?></td>
                                 <td>
                                    <a href="<?php echo route('menuItemCategory.edit', $menuItemCategory->item_cat_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> 
                                    | 
                                    <?php echo Form::open(['route' => array('menuItemCategory.destroy', $menuItemCategory->item_cat_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
                  <?php echo $menuItemcategories->links(); ?>
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
      $( "#sortable" ).sortable({
         update: function( ) {
            var sortIndex = [];
            $.each($('.tr_rows'), function(index, val) {
               sortIndex.push($(this).attr('data-item_cat_id'));
            });
            $.ajax({
               url: '<?php echo route('menuItemCategory.updateOrder') ?>',
               type: 'GET',
               data: {order: sortIndex},
            });            
         }
      });
   });
</script><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Admin/MenuItemsCategory/Index.blade.php ENDPATH**/ ?>
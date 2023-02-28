<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-6">
                        <h5 class="m-b-10">Menu Item</h5>
                     </div>
                     <div class="col-md-6">
                        <a href="<?php echo route('menuItem.create') ?>" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>

                  <form action="" method="get">
                     <div class="row">
                        <div class="col-md-8">
                           <input type="text" name="item_name" value="<?php echo Request()->get('item_name') ?>" class="form-control" placeholder="Search Keywords...">
                        </div>
                        <div class="col-md-4">
                           <button type="submit" class="btn btn-info">Search</button>
                        </div>
                     </div>
                  </form>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Item Name</th>
                              <th>Item Price</th>
                              <th>Item Sale Price</th>
                              <th>Discount</th>
                              <th>Type</th>
                              <th>Last Update Date</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody id="sortable">
                           <?php 
                           foreach ($menuItems as $menuItem) {
                           	$price = $menuItem->item_price;
                           	if ($menuItem->item_sale_price) {
                           		$price = $menuItem->item_sale_price;
                           	}
                           	$discountPrice = 0;
                           	if ($menuItem->item_discount_start <= date('Y-m-d') && $menuItem->item_discount_end >= date('Y-m-d')) {
                           		$discount = ($price * $menuItem->item_discount / 100);
                           		$discountPrice = $price - $discount;
                           	}
                              ?>
                              <tr class="tr_rows" data-menu_item_id="<?php echo $menuItem->menu_item_id; ?>">
                                 <td><?php echo $menuItem->item_name ?></td>
                                 <td><?php echo priceFormat($menuItem->item_price) ?></td>
                                 <td><?php echo priceFormat($menuItem->item_sale_price) ?></td>
                                 <td><?php echo priceFormat($discountPrice); ?></td>
                                 <td><?php echo $menuItem->item_is; ?></td>
                                 <td><?php echo dateFormat($menuItem->updated_at); ?></td>
                                 <td>
                                    <a href="<?php echo route('menuItem.edit', $menuItem->menu_item_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> 
                                    | 
                                    <?php 
                                    if ($menuItem->item_is == 'Attributes') {
                                       ?>
                                       <a href="<?php echo route('menuItemAttributes.index') ?>?menu_item_id=<?php echo $menuItem->menu_item_id ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-settings"></i></span></button></a> 
                                       |
                                       <?php
                                    } ?>                                    
                                    <?php echo Form::open(['route' => array('menuItem.destroy', $menuItem->menu_item_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
                  <?php echo $menuItems->links(); ?>
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
               sortIndex.push($(this).attr('data-menu_item_id'));
            });
            $.ajax({
               url: '<?php echo route('menuItem.updateOrder') ?>',
               type: 'GET',
               data: {order: sortIndex},
            });            
         }
      });
   });
</script><?php /**PATH C:\xampp\htdocs\pza\resources\views/Admin/MenuItems/Index.blade.php ENDPATH**/ ?>
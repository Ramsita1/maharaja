<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-6">
                        <h5 class="m-b-10">Menu Over views</h5>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <ul class="list-group">
                        <?php 
                        foreach ($menuItemcategories as $menuItemcategory) {
                           ?>
                           <li class=""> <a class="list-group-item list-group-item-info"><?php echo $menuItemcategory['cat_name'] ?></a>
                           <?php 
                           if (isset($menuItemcategory['menuItems']) && !empty($menuItemcategory['menuItems'])) {
                              ?>
                               <ul class="list-group sortable" id="sortable_<?php echo $menuItemcategory['item_cat_id'] ?>" style="padding-left: 30px;">
                              <?php
                              foreach ($menuItemcategory['menuItems'] as $menuItem) {
                                 ?>
                                 <li data-menu_item_id="<?php echo $menuItem['menu_item_id']; ?>" class="list-group-item list-group-item-warning tr_rows"><?php echo $menuItem['item_name'] ?></li>
                                 <?php
                              }
                              ?>
                              </ul>
                              <?php
                           }
                           ?>
                           </li>
                           <?php
                        }
                        ?>
                         
                     </ul>
                  </div>
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
      $( ".sortable" ).sortable({
         update: function( ) {
            var sortIndex = [];
            var $this = $(this);
            var thisID = $this.attr('id');
            $.each($('#'+thisID+' .tr_rows'), function(index, val) {
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
</script>
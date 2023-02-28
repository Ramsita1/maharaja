<div class="col-md-6 <?php echo $catName ?> allItems">
   <ul class="menu-dish">
      <li>
         <figure class="dish-entry">
            <div class="dish-img" style="background-image: url(<?php echo publicPath().'/'.$menuItem->item_image ?>);"></div>
         </figure>
         <div class="text">
            <h3><?php echo $menuItem->item_name ?></h3>
            <p class="cat"><?php echo $menuItem->item_description ?></p>
            <span class="price"><?php echo priceFormat(itemShowPrice($menuItem)); ?></span>
         </div>
         <div class="dish-btn-blk">
            <a data-toggle="modal" data-target="#itemAttributeMOdal" class="dish-btn addToCartAttr" data-store_id="<?php echo $menuItem->store_id; ?>" data-menu_item_id="<?php echo $menuItem->menu_item_id; ?>">Add +</a>
         </div>
      </li>
   </ul>
</div><?php /**PATH C:\xampp2\htdocs\maharaja-hotel\resources\views/Estore/ItemAttributes.blade.php ENDPATH**/ ?>
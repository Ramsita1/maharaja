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
            <?php echo addToCartButton($menuItem);?>            
         </div>
      </li>
   </ul>
</div>
<!-- <div class="menu-mob">
   <div class="menu-mob-inner">
      <a id="mob_menu"><img src="<?php echo publicPath() ?>/front/images/menu.png"> Menu</a>
      <ul class="mob-cat">
         <li><a href="#">Cat Name1</a></li>
         <li><a href="#">Cat Name2</a></li>
         <li><a href="#">Cat Name3</a></li>
         <li><a href="#">Cat Name4</a></li>

      </ul>
   </div>
</div> -->
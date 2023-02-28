<?php echo headerCommon($post); ?>

<div id="colorlib-contact">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php 				
				$cartDatas = Session::get ( 'cartData' );
				if (!empty($cartDatas)) {
					?>
					<div class="table-responsive">
					   <table id="cart" class="table table-hover table-condensed">
					      <thead>
					         <tr>
					            <th style="width:50%">Item</th>
					            <th style="width:10%">Price</th>
					            <th style="width:8%">Quantity</th>
					            <th style="width:22%" class="text-center">Subtotal</th>
					            <th style="width:10%"></th>
					         </tr>
					      </thead>
					      <tbody>
					      	<?php
					      	$subTotal = 0; 
					      	foreach ($cartDatas as $cartData) {
					      		$menuItem = \App\MenuItems::where('menu_item_id', $cartData['menu_item_id'])->where('store_id', $cartData['store_id'])->get()->first();
					      		$subTotal += $cartData['item_total_price'];
					      		$attributes = ' data-store_id="'.$menuItem->store_id.'"';
					      		$attributes .= ' data-menu_item_id="'.$menuItem->menu_item_id.'"';
					      		$attributes .= ' data-item_name="'.$menuItem->item_name.'"';
					      		$attributes .= ' data-item_price="'.$cartData['item_price'].'"';
					      		$attributes .= ' data-item_page="cartPage"';
					      		?>				      		
					      		<tr>
					      		   <td data-th="Product">
					      		      <div class="row">
					      		         <div class="col-sm-2 hidden-xs"><img src="<?php echo publicPath().'/'.$menuItem->item_image ?>" alt="<?php echo $menuItem->item_name ?>" class="img-responsive"/></div>
					      		         <div class="col-sm-10">
					      		            <h4 class="nomargin"><?php echo $menuItem->item_name ?></h4>
					      		         </div>
					      		      </div>
					      		   </td>
					      		   <td data-th="Price"><?php echo priceFormat($cartData['item_price']) ?></td>
					      		   <td data-th="Quantity">
					      		      <input type="number" class="form-control text-center quantity_<?php echo $menuItem->menu_item_id ?>" value="<?php echo $cartData['item_quantity'] ?>">
					      		   </td>
					      		   <td data-th="Subtotal" class="text-center"><?php echo priceFormat($cartData['item_total_price']) ?></td>
					      		   <td class="actions" data-th="">
					      		      <button class="btn btn-info btn-sm addToCartItem" <?php echo $attributes ?>><i class="fa fa-refresh"></i></button>
					      		      <button class="btn btn-danger btn-sm deleteItemFromCart" <?php echo $attributes ?>><i class="fa fa-trash-o"></i></button>								
					      		   </td>
					      		</tr>
					      	<?php
					      	}
					      	?>
					      </tbody>
					      <tfoot>
					         <tr class="visible-xs">
					            <td class="text-center"><strong>Total <?php echo priceFormat($subTotal) ?></strong></td>
					         </tr>
					         <tr>
					            <td></td>
					            <td colspan="2" class="hidden-xs"></td>
					            <td class="hidden-xs text-center"><strong>Total <?php echo priceFormat($subTotal) ?></strong></td>
					            <td><a href="<?php echo url('checkout') ?>" class="btn btn-success btn-block">Proceed Order <i class="fa fa-angle-right"></i></a></td>
					         </tr>
					      </tfoot>
					   </table>
					</div>
				<?php 
				} else {
					?>
					<h4 class="text-center">Your cart is empty</h4>
					<div class=" text-center"><a class="btn btn-success" href="<?php echo url('/') ?>">Continue to Order</a></div>
					<?php
				} ?>
			</div>
		</div>
	</div>
</div>

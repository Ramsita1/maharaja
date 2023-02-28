<?php 
$currentUser = getCurrentUser();
if (!$currentUser->user_id) {
	echo view('Templates.LoginRegister', compact('post'));
} else {
	?>
	<?php echo headerCommon($post); ?>
	<div id="colorlib-contact">
	   <div class="container">
	      <div class="row">
	         <div class="table-responsive">
	            <table id="cart" class="table table-hover table-condensed">
	               <thead>
	                  <tr>
	                     <th># Order</th>
	                     <th>Transaction ID</th>
	                     <th>date</th>
	                     <th>Status</th>
	                     <th>Total</th>
	                     <th>Action</th>
	                  </tr>
	               </thead>
	               <tbody>
	               		<?php 
	               		$orders = \App\ProductOrder::where('user_id', $currentUser->user_id)->orderBy('order_id', 'DESC')->paginate(pagination());
	               		foreach ($orders as $order) {
	               		   $order->attributes = maybe_decode($order->attributes);
	               		   $order->product_detail = maybe_decode($order->product_detail);
	               		   $order->billing_address = maybe_decode($order->billing_address);
	               		   $order->shipping_address = maybe_decode($order->shipping_address);
	               		   ?>
	               		   <tr>
	               		      <td>
	               		         <div class="first-sec"><span class="o-id">#<?php echo $order->order_id; ?></span></div>
	               		      </td>
	               		      <td>
	               		         <div class="first-sec"><span class="o-id">#<?php echo $order->transaction_id; ?></span></div>
	               		      </td>
	               		      <td>
	               		         <h6><?php echo dateFormat($order->created_at); ?></h6>
	               		      </td>
	               		      <td><a href="#" class="order-status <?php echo ($order->order_status);?>"><?php echo ucfirst($order->order_status);?></a></td>
	               		      <td style="white-space: nowrap;"><b><?php echo priceFormat($order->grand_total); ?></b></td>
	               		      <td style="white-space: nowrap;">
	               		         <button type="button" class="btn btn-info"><i class="fa fa-eye"></i></button> 
	               		         <span> | <button type="button" class="btn btn-danger"><i class="fa fa-ban"></i></button></span>
	               		      </td>
	               		   </tr>
	               		   <?php
	               		}
	               		?>
	               </tbody>
	            </table>
	         </div>
	         <?php echo $orders->links(); ?>
	      </div>
	   </div>
	</div>
<?php 
}
?><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Templates/MyOrder.blade.php ENDPATH**/ ?>
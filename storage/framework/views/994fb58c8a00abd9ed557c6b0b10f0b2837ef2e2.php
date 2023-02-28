<div class="container" id="parentContanier">
   <?php 
   	$cartDatas = Session::get ( 'cartData' );
	$delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
	if (empty($cartDatas) || empty($delivery_pickup_address)) {
	    Session::flash ( 'warning', "Your cart is empty." );
	    header("Location: " . URL::to('/'), true, 302);
	    exit();
	}
	if (Request()->get('page') == 'payment') {
		$order = \App\ProductOrder::where('transaction_id', Request()->get('transaction_id'))->get()->first();
		if (!$order) {
         autoApplyDealsCheck();
			echo view('Estore.StoreCheckout');
		} else {
			echo view('Estore.StorePayment', compact('order'));
		}      	
	} else {
         autoApplyDealsCheck();
      	echo view('Estore.StoreCheckout');
   	}
   ?>
</div><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Templates/Checkout.blade.php ENDPATH**/ ?>
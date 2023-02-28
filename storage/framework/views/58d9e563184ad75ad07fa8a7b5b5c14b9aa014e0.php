<style>
   .order-unsuccess{margin-top:30px;margin-bottom:30px;}
   .order-unsuccess{border:1px solid #efefef;padding:30px 15px 20px;text-align:center;}
   .order-unsuccess h2{color: #fe6107;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;}
   .order-unsuccess h3{font-size: 18px;
    color: #000;
    font-weight: 600;
    margin-bottom: 25px;}
   .order-unsuccess h4 {    color: #fe6107;
    border-top: 1px solid #eee;
    padding: 10px 0px;
    margin: 20px 25%;}
   .order-unsuccess .btn-try{font-size: 14px;text-transform: uppercase;
    color: #fff;background:#fe6107;display:inline-block;padding:5px 30px;
    margin-bottom: 25px;border-radius:30px;}
   .order-unsuccess p{margin-bottom:4px;}
.unscess-sidebar{border: 1px solid #efefef;
    padding: 10px 15px;margin-top:30px;margin-bottom:30px;clear:both;}
.unscess-sidebar h2{font-size: 16px;
    color: #fe6107;margin-bottom:10px;
    font-weight: 600;}
.unscess-sidebar div{    font-size: 12px;}
.unscess-sidebar div b{margin-bottom:5px;display:block;}
.unscess-sidebar ul{padding-left: 0px;
    list-style: none;
    border-top: 1px solid #bfbfbf;
    border-bottom: 1px solid #bfbfbf;
    margin: 10px 0px;
    font-size: 12px;
    padding-top: 6px;
    padding-bottom: 6px;width:100%;float: left;}
.unscess-sidebar ul li    {    float: left;
    width: 100%;}
.unscess-sidebar ul li span{float: right;
    margin-bottom: 0px;
    font-size: 13px;}
 .unscess-sidebar h3   {margin-bottom: 10px;
    font-size: 16px;
    font-weight: 600;
    color: #fe6107;
    padding-top: 10px;clear:both;}
 .unscess-sidebar h3 span    {float: right;
    color: #000;
    font-size: 15px;margin-bottom:0px;}
  .unscess-sidebar h3 span.pay-mode  {color: #848484;
    font-size: 12px;}
.track-order{border: 1px solid #efefef;
    padding: 30px 15px 20px;width:100%;float:left;margin-bottom:20px;
    }
    .track-order-left{    float: left;
    width: 60%;}
     .track-order-left h2{color: #fe6107;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;}
     .track-order-left p{    font-size: 18px;
    color: #000;
    font-weight: 600;
    margin-bottom:0px;line-height:22px;}
      .track-order-left h4{    color: #fe6107;
    border-top: 1px solid #eee;
    padding: 15px 0px;
    margin: 20px 0px;}
    .playstore-div img{margin-bottom:10px;}
    .track-order-ryt{float: left;
    width: 40%;
    text-align: right;}
   .track-order-ryt img {
    width: calc(100% - 130px);
}
@media  screen and (max-width:767px)
{
   .order-unsuccess h4{margin:20px 0px;}
   .track-order-left {
    float: left;
    width: 100%;
}
.track-order-ryt {
    width: 100%;
    text-align: left;
}
}    
</style>

<?php 
	$transaction_id = Request()->get('transaction_id');
	if (empty($transaction_id)) {
		?>
		<script type="text/javascript">
			window.location.href="<?php echo url('cancel') ?>";
		</script>
		<?php
		exit;
	}
	$order = \App\ProductOrder::where('transaction_id', $transaction_id)->get()->first();

	if (empty($order)) {
		?>
		<script type="text/javascript">
			window.location.href="<?php echo url('cancel') ?>";
		</script>
		<?php
		exit;
	}
	$orderDatas = maybe_decode($order->attributes);
	$product_details = maybe_decode($order->product_detail);
	$billing_address = maybe_decode($order->billing_address);
	$shipping_address = maybe_decode($order->shipping_address);
  $store = \App\Stores::where('store_id', $order->store_id)->get()->first();
?>
<div class="container">
   <div class="row">
      <div class="checkout-wrap">
         <div class="col-md-8">
            <div class="order-unsuccess">
               <!-- <p><?php echo $post->post_content; ?></p> -->
               <h2>Thank You!</h2>
               <h3>We have received your order. You will get an 
                  SMS as soon as it is confirmed.
               </h3>
               <h4>Save your information for future orders!</h4>
               <a href="#" class="btn-try">Sign Up</a>
               <p>Need Assistance? Call us on <a href="#" tel="03-94710398" >03-94710398</a></p>
            </div>
            <div class="track-order">
               <div class="track-order-left">
                  <h2>Track Your Order!</h2>
                  <p>Get Realtime Order Updates 
                     & Exclusive Discounts
                  </p>
                  <h4>Download App Now</h4>
                  <div class="playstore-div">
                     <img src="<?php echo publicPath() ?>/front/images/app-store.png"><br>
                     <img src="<?php echo publicPath() ?>/front/images/google-play.png">
                  </div>
               </div>
               <div class="track-order-ryt">
                  <img src="<?php echo publicPath() ?>/front/images/track-mobile.png">
               </div>
            </div>
         </div>
         <div class="col-md-4">
            <div class="unscess-sidebar">
               <h2>Your Order</h2>
               <div>Order ID</div>
               <div><b><?php echo $transaction_id; ?></b></div>
               <?php 
               if ($shipping_address['order_type'] == 'Pickup') {
                 ?>
                 <div>Pickup From</div>
                  <div><b><?php echo $store->store_city.' '.$store->store_postalCode ?></b>
                 <?php
               } else {
                  ?>
                 <div>Deliver To</div>
                  <div><b><?php echo $shipping_address['city'].' '.$shipping_address['pincode'] ?></b>
                 <?php
               }
               ?>               
               </div>
               <div>When</div>
               <?php 
               if (isset($shipping_address['pickup_when']) && $shipping_address['pickup_when'] == 'Now')
               {
                 ?>
                 <div><b>ASAP</b></div>
                 <?php
               } elseif (isset($shipping_address['pickup_when']) && $shipping_address['pickup_when'] == 'Later')
               {
                 ?>
                 <div><b><?php echo date('h:i A', strtotime($shipping_address['order_time'])) ?></b></div>
                 <?php
               }
               ?>
               
               <ul>
                  <?php 
                  if (!empty($orderDatas) && is_array($orderDatas)) {
                    $itemCats = [];
                    foreach ($orderDatas as $orderData) {
                      $item_category = \App\MenuItems::where('menu_item_id', $orderData['menu_item_id'])->get()->pluck('item_category')->first();
                      $itemCats[$item_category][] = $orderData['menu_item_id'];
                    }
                    foreach ($itemCats as $item_category => $menu_item_ids) {                      
                      $cat_name = \App\MenuItemsCategory::where('item_cat_id', $item_category)->get()->pluck('cat_name')->first();
                      ?>
                      <li><h5 style="color: red; margin-bottom: 10px; margin-top: 20px;"><?php echo $cat_name ?></h5></li>
                      <?php
                      foreach ($menu_item_ids as $menu_item_id) {
                        foreach ($orderDatas as $orderData) {
                          if ($orderData['menu_item_id'] == $menu_item_id) {
                               $totalAttrButePrice = 0;
                               $attributeName = [];
                               if (isset($orderData['attributes'])) {
                                  foreach ($orderData['attributes'] as $attribute) {
                                     $attributeName[] = $attribute['attr_name'];
                                      $price_type = '+';
                                      if ($attribute['attr_type'] == 'remove') {
                                        $totalAttrButePrice -= $attribute['attr_total_price'];
                                          $price_type = '-';
                                      } else{
                                          $totalAttrButePrice += $attribute['attr_total_price'];
                                      }
                                  }
                               }
                            ?>
                            <li style="margin-bottom: 5px;"><?php echo $orderData['item_name'];  if ($attributeName) {
                               ?>
                               (<?php echo implode(', ', $attributeName) ?>)
                               <?php
                              }?> <span>x <?php echo  $orderData['item_quantity'] ?></span></li>
                            <?php
                          }
                        }
                      }
                    }
                  }
                  ?>
               </ul>
               <h3>Amount <span><?php echo priceFormat($order->grand_total) ?></span></h3>
               <h3>Payment Mode <span class="pay-mode"><?php echo strtoupper($order->payment_getway) ?></span></h3>
            </div>
         </div>
      </div>
   </div>
</div><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Templates/ThankYou.blade.php ENDPATH**/ ?>
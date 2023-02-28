<?php 
  $cartDatas = Session::get ( 'cartData' );
  $delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
  if (empty($cartDatas) || empty($delivery_pickup_address)) {
    Session::flash ( 'warning', "Your cart is empty." );
    header("Location: " . URL::to('/'), true, 302);
    exit();
  }
  $minimumOrderPrice = $delivery_pickup_address['minimumOrderPrice'];
  $store_id = array_column($cartDatas, 'store_id');
  $store_id = reset($store_id);
  $menu_item_id = array_column($cartDatas, 'menu_item_id');
  
  $order_type = $delivery_pickup_address['order_type'];
  $order_date = $delivery_pickup_address['order_date'];
  $order_time = $delivery_pickup_address['order_time'];
  $slot = $delivery_pickup_address['slot'];
  $store = \App\Stores::where('store_id', $store_id)->get()->first();
  $couponCode = (isset($delivery_pickup_address['couponCode'])?$delivery_pickup_address['couponCode']:'');
  $deal_id = (isset($delivery_pickup_address['deal_id'])?$delivery_pickup_address['deal_id']:'');
  $couponType = (isset($delivery_pickup_address['couponType'])?$delivery_pickup_address['couponType']:'');
  if ($couponType == 'deal') {
    $voucher = \App\Deals::where('deal_id', $deal_id)->get()->first();
  } else {
    $voucher = \App\Vouchers::where('code', $couponCode)->get()->first();
  }
  $currentUser = getCurrentUser();
  $payment_getway = getThemeOptions('payment_getway'); 
  $header = getThemeOptions('header');
  /*echo '<pre>';
  print_r($voucher);
  echo '</pre>';*/
  ?>
   <style type="text/css">
    .couponPromo {
         border: 1px solid #bbb;
    width: 100%;
    border-radius: 0px;
    margin: 0 auto;
    margin-top: 20px;
    margin-bottom: 20px;
    max-width: 600px;
    padding: 20px;
    height: 210px;
    overflow-y: scroll;
    }
.couponPromo h2{ 
   
    font-size: 16px;
    text-transform: uppercase;margin-bottom:20px;}
    .containerCoupon {
      padding-top:10px;
      background-color: #fff;
      display: block;
      min-height: 71px;
    }
 .containerCoupon h4{margin-bottom:20px;font-size:14px;}
  .containerCoupon h4 b{border: 1px dashed #f00;
    padding: 5px 10px;}
 .containerCoupon  p{margin-bottom:10px;font-size:14px;}
    .promoCoupon {
      background: #ccc;
      padding: 3px;
    }
.coupon-box{     border-bottom: 2px dashed #bbb;
    margin-bottom: 20px;
    clear: both;
    width: 100%;
    float: left;
    padding-bottom: 10px;}
    .expire {
    color: red;
    width: 150px;
    float: left;
    margin-bottom: 0px!important;
    line-height: 41px;
      
    }
    .applyDealBtn {
      float:left;
      width: 140px;
    background: #ff6107;
    border-color: #ff6107;
    }
    .applyPromoCode{
      min-height: 50px;
    }
    #removePromoCodeAction{
      position: absolute;
      top: 5px;
      right: 0;
    }
      a.tipOption.active {
          background: #FF6107;
          color: #fff;
      }
      #verifyPhone {
        cursor: pointer;
      }
      #special_instructions {
        display: none;
      }
   </style>
   <?php $queryString = http_build_query(Request()->all()); ?>
   <div class="row checkoutDetails">
      <div class="back-btn"><a href="<?php echo url('/estore/items').'?'.$queryString; ?>" ><span class="back-ico"><img src="<?php echo publicPath() ?>/front/images/back-btn.png"></span><span class="back-txt">Back</span></a></div>
      <div class="checkout-wrap">
         <div class="col-md-7">
            <div class="checkout-slide">
               <h2>You may also like</h2>
               <section class="checkout slider" id="checkoutAlsoLikeSlider">
                <?php 
                $menuItems = \App\MenuItems::where('menu_items.store_id', $store_id)->whereNotIn('menu_item_id', $menu_item_id)
                            ->select('menu_items.*')
                            ->where(function($query) use($slot){
                                if ($slot) {
                                    $qu = '"'.$slot.'":{"status":"1"';
                                    $query->whereRaw("item_for LIKE '%".$qu."%'");
                                    //$query->whereRaw("json_extract(item_for, '$.".$slot.".status') = 1");
                                }
                            })
                            ->where('is_you_may_like', 'Yes')
                            ->limit($store->store_you_may_like_item_show_count)
                            ->get();
                foreach ($menuItems as $menuItem) {
                  ?>
                  <div>
                         <img src="<?php echo publicPath().'/'.$menuItem->item_image ?>" class="checkout-icon">
                         <div class="checkout-detail">
                            <h4><?php echo $menuItem->item_name ?></h4>
                            <p><?php echo priceFormat(itemShowPrice($menuItem)); ?></p>
                            <div class="dish-btn-blk">
                               <?php 
                                if($menuItem->item_is == 'Attributes'){
                                  ?>
                                  <a data-toggle="modal" data-target="#itemAttributeMOdal" class="dish-btn addToCartAttr" data-store_id="<?php echo $menuItem->store_id; ?>" data-item_page="checkoutPage" data-menu_item_id="<?php echo $menuItem->menu_item_id; ?>">Add +</a>
                                  <span class="cust">customizable</span>
                                  <?php
                                } else {
                                  echo addToCartButton($menuItem, 'checkoutPage');
                                }?>  
                               
                            </div>
                         </div>
                      </div>
                  <?php
                }
                ?>
               </section>
            </div>
            <div class="checkout-order">
               <h2>Order Details</h2>
               <ul>
                <?php 
                if ($delivery_pickup_address['order_type'] == 'Pickup') {
                  ?>
                  <li>
                      <span class="left">Pickup From <!-- <a href="#"><img src="<?php echo publicPath() ?>/front/images/edit.png"></a> --></span>
                      <span class="right"><?php echo $store->store_address ?>, <?php echo $store->store_city ?> <?php echo $store->store_postalCode ?></span>
                  </li>
                  <?php
                } else {
                  ?>
                  <li>
                      <span class="left">Delivery To <!-- <a href="#"><img src="<?php echo publicPath() ?>/front/images/edit.png"></a> --></span>
                      <span class="right"><u><?php echo $delivery_pickup_address['city'].' '.$delivery_pickup_address['pincode'] ?></u></p></span>
                  </li>
                  <?php
                }
                if (isset($delivery_pickup_address['pickup_when']) && $delivery_pickup_address['pickup_when'] == 'Now')
                {
                  ?>
                  <li>
                     <span class="left">When?</span>
                     <span class="right">ASAP</span>
                  </li>
                  <?php
                } elseif (isset($delivery_pickup_address['pickup_when']) && $delivery_pickup_address['pickup_when'] == 'Later')
                {
                  ?>
                  <li>
                     <span class="left">When?</span>
                     <span class="right"><?php echo date('M d, Y', strtotime($delivery_pickup_address['order_date'])).' '.date('h:i A', strtotime($delivery_pickup_address['order_time'])) ?></span>
                  </li>
                  <?php
                }
                ?>                  
                  <li>
                    <?php 
                    if ($delivery_pickup_address['order_type'] == 'Delivery') {
                      ?>
                      <span class="left">Minimum order for  <?php echo $delivery_pickup_address['city'].' '.$delivery_pickup_address['pincode'] ?></span>
                      <span class="right"><?php echo priceFormat($minimumOrderPrice) ?></span>
                      <?php
                    }
                    ?>
                     
                  </li>
               </ul>
            </div>
            <div class="checkout-form">
              <div class="col-md-12">
                <div class="alert-danger hide showWarningMessage" id="showWarningMessageScroll"></div>
              </div>
               <form action="" class="checkout-form-submit">
                <?php
                $hideClass = '';
                if ($currentUser->name) {
                  $hideClass = "hide";
                }
                ?>
                  <div class="form-group <?php echo $hideClass; ?>">
                     <label for="checkout-name">Full Name</label>
                     <div class=" inputGroupContainer">
                        <div class="input-group"><input id="checkout-name" name="name" placeholder="Full Name" class="form-control" required="true" value="<?php echo (isset($delivery_pickup_address['name'])?$delivery_pickup_address['name']:$currentUser->name) ?>" type="text"><span class="input-group-addon"><i style="color:red;" class="glyphicon glyphicon-remove-circle"></i></span></div>
                        <span class="error-span" style="display:none;">Name is required</span>
                     </div>
                  </div>
                  <?php
                  $hideClass = '';
                  if ($currentUser->email) {
                    $hideClass = "hide";
                  }
                  ?>
                  <div class="form-group <?php echo $hideClass; ?>">
                     <label for="checkout-email">Email</label>
                     <div class=" inputGroupContainer">
                        <div class="input-group"><input id="checkout-email" name="email" placeholder="Email" class="form-control" required="true" value="<?php echo (isset($delivery_pickup_address['email'])?$delivery_pickup_address['email']:$currentUser->email) ?>" type="email"><span class="input-group-addon"><i style="color:red;" class="glyphicon glyphicon-remove-circle"></i></span></div>
                      <span class="error-span" style="display:none;">Email is required</span>
                     </div>
                  </div>
                  <?php
                  $hideClass = '';
                  if ($currentUser->phone) {
                    $hideClass = "hide";
                  }
                  ?>
                  <div class="form-group <?php echo $hideClass; ?>">
                     <label for="checkout-phone">Phone</label>
                     <div class=" inputGroupContainer">
                        <div class="input-group"><input id="checkout-phone" minlength="10" maxlength="10" name="phone" placeholder="Phone" class="form-control InputNumber" required="true" value="<?php echo (isset($delivery_pickup_address['phone'])?$delivery_pickup_address['phone']:$currentUser->phone) ?>" type="text"><span class="input-group-addon"><i style="color:red;" class="glyphicon glyphicon-remove-circle"></i></span></div>
                        <span class="error-span" style="display:none;">Phone is required</span>
                     </div>
                  </div>
                  
                  <div class="form-group" id="otpShow" style="display: none;">
                     <label>Otp</label>
                     <div class=" inputGroupContainer">
                        <div class="input-group"><input id="otp" name="otp" placeholder="otp" class="form-control" required="true" value="" type="text"><span class="input-group-addon"><i style="color:red;" class="glyphicon glyphicon-remove-circle"></i></span></div>
                     </div>
                     <button type="button" class="btn btn-succes" id="verifyPhone">Verify</button>
                  </div>
                  <div class="checkbox terms_div">
                    <h3>Get Special Offers </h3>
                     <label><input type="checkbox" checked id="accpet_term_condition" name="accpet_term_condition" value="1"> I agree to Maharaja's Terms & Conditions & Privacy Policy</label>
                  </div>
               </form>
            </div>
         </div>
         <div class="col-md-5">
            <div class="checkout-sidebar">
               <h2>Order Summary</h2>
               <ul class="menu-dish">
                <?php
                $subTotal = 0;
                $catVDExpertPrice = 0; 
              foreach ($cartDatas as $cartData) {
                $menuItem = \App\MenuItems::where('menu_item_id', $cartData['menu_item_id'])->where('store_id', $cartData['store_id'])->get()->first();
                
                if ($voucher && (($voucher->category_id && $menuItem->item_category == $voucher->category_id) || (isset($voucher->menu_item_id) && $voucher->menu_item_id && $voucher->menu_item_id == $menuItem->menu_item_id)) || $menuItem->is_non_discountAble == 1) {
                  $catVDExpertPrice += $cartData['item_total_price'];
                } else {
                  $subTotal += $cartData['item_total_price'];
                }              
                $attributes = ' data-store_id="'.$menuItem->store_id.'"';
                $attributes .= ' data-menu_item_id="'.$menuItem->menu_item_id.'"';
                $attributes .= ' data-item_name="'.$menuItem->item_name.'"';
                $attributes .= ' data-item_price="'.$cartData['item_price'].'"';
                $attributes .= ' data-item_page="checkoutPage"';
                $attributeIDS = '';

                if ($menuItem->item_is == 'Attributes') {
                  $attributes .= ' data-type="attribute"';
                }
                if (isset($cartData['attributes'])) {
                  $attributes .= ' data-type="attribute"';
                  $arrayKeys = array_keys($cartData['attributes']);
                  $attributeIDS = implode('-',$arrayKeys);
                  $attributes .= ' data-item_attributeIDS="'.$attributeIDS.'"';
                }
                $totalItemPrice = $cartData['item_total_price'];
                  ?>  
                  <li>
                     <div class="text">
                        <h3><?php echo $menuItem->item_name ?></h3>
                        <?php 
                          $totalAttrButePrice = 0;
                          $totalattr_price = 0;
                          $attributeName = [];
                          if (isset($cartData['attributes'])) {
                              foreach ($cartData['attributes'] as $attribute) {
                                  $attributeName[] = $attribute['attr_name'];
                                  $price_type = '+';
                                  if ($attribute['attr_type'] == 'remove') {
                                      $price_type = '-';
                                      $subTotal -= $attribute['attr_total_price'];
                                      $totalattr_price -= $attribute['attr_price'];
                                      $totalAttrButePrice -= $attribute['attr_total_price'];
                                  } else{
                                      
                                      if ($voucher && (($voucher->category_id && $menuItem->item_category == $voucher->category_id) || (isset($voucher->menu_item_id) && $voucher->menu_item_id && $voucher->menu_item_id == $menuItem->menu_item_id)) || $menuItem->is_non_discountAble == 1) {
                                        $catVDExpertPrice += $attribute['attr_total_price'];
                                      } else {
                                        $subTotal += $attribute['attr_total_price'];
                                      }
                                      $totalattr_price += $attribute['attr_price'];
                                      $totalAttrButePrice += $attribute['attr_total_price'];
                                  }
                              }
                          }
                          ?> 
                        <span class="price"><?php echo priceFormat($cartData['item_price']+$totalattr_price) ?></span>
                        <p class="cat">
                          <?php echo implode(', ', $attributeName); ?>
                        </p>
                     </div>
                     <div class="dish-btn-blk">
                        <div class="dish-btn-qblk">
                           <div class="dish-btn-qty dish_qty_1" style="">
                              <div class="input-group">
                                 <span class="input-group-btn">
                                 <button type="button" class="quantity-left-minus btn btn-number" <?php echo $attributes ?>>
                                 <span>-</span>
                                 </button>
                                 </span>
                                 <input type="text" name="quantity" class="form-control input-number quantity_<?php echo $menuItem->menu_item_id ?>" value="<?php echo $cartData['item_quantity'] ?>" min="1" max="100">
                                 <span class="input-group-btn">
                                 <button type="button" class="quantity-right-plus btn btn-number" <?php echo $attributes ?>>
                                 <span>+</span>
                                 </button>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <?php echo priceFormat($cartData['item_total_price']+$totalAttrButePrice) ?>     
                     </div>
                  </li>
                  <?php
                  if ($couponType == 'deal' && $voucher && in_array($voucher->deal_type, ['BGF', 'FI'])) {
                    $menu_item_id = array_column($cartDatas, 'menu_item_id');
                    $menuItemID = $voucher->get_item;
                    $offerItemQTY = $voucher->get_item_qnty;
                    $buyItemID = $voucher->buy_item;
                    if ($voucher->deal_type == 'FI') {
                      $menuItemID = $voucher->menu_item_id;
                      $buyItemID = $voucher->menu_item_id;
                      $offerItemQTY = 1;
                    }
                    if ($buyItemID == $cartData['menu_item_id']) {
                      $offerMenuItem = \App\MenuItems::where('menu_item_id', $menuItemID)->get()->first();
                      ?>  
                      <li style="padding-bottom: 30px;">
                        <h4 style="margin-bottom: 0px;">Deal Item</h4>
                         <div class="text">
                            <h3><?php echo $offerMenuItem->item_name ?></h3> 
                            <span class="price"><?php echo priceFormat(0) ?></span>
                         </div>
                         <div class="dish-btn-blk">
                            <div class="dish-btn-qblk">
                               <div class="dish-btn-qty dish_qty_1" style="">
                                  <div class="input-group">
                                     <input type="text" readonly="" class="form-control input-number" value="<?php echo $offerItemQTY ?>">
                                  </div>
                               </div>
                            </div>
                            <?php echo priceFormat(0) ?>     
                         </div>
                      </li>
                      <?php
                    }                    
                  }
              }
                ?>                 

               </ul>
               <div class="specialize">
                  Any Special Instructions For Kitchen <span><a onclick="$('#special_instructions').toggle()">Add</a></span>
                  <textarea name="special_instructions" rows="10" id="special_instructions" class="form-control" placeholder="Any Special Instructions For Kitchen"></textarea>
               </div>
               <div class="promo">
                <?php
                if (isset($delivery_pickup_address['couponCode']) && !empty($delivery_pickup_address['couponCode'])) {
                  ?>
                  <div class="applyPromoCode">
                    <p class="couponAlert" style="display: none;"></p>
                    <span><img src="<?php echo publicPath() ?>/front/images/sale.png"></span><a>Promo Code</a>
                    <button type="button" id="removePromoCodeAction" class="btn btn-warning"><?php echo $delivery_pickup_address['couponCode'] ?> X</button>
                  </div>
                  <?php
                } else {
                   ?>
                     <span><img src="<?php echo publicPath() ?>/front/images/sale.png"></span><a onclick="$('#applyPromoCode').modal('show');">Click to Apply Promo Code</a>
                  <?php
                }  
                ?>
              </div>
                <?php               
                  if ($store->store_enable_tip == 'yes' && !empty($store->store_delivery_boy_tips)) {
                     $store_delivery_boy_tips = explode(',', $store->store_delivery_boy_tips);
                     ?>
                     <div class="tip-rider">
                        Tip your Rider 
                        <?php 
                        if (is_array($store_delivery_boy_tips) && !empty($store_delivery_boy_tips)) {
                           $store_delivery_boy_tips = array_reverse($store_delivery_boy_tips);
                           foreach ($store_delivery_boy_tips as $store_delivery_boy_tip) {
                              $activeClass = (isset($delivery_pickup_address['tipPrice']) && $delivery_pickup_address['tipPrice'] == $store_delivery_boy_tip?'active':'');
                              ?>
                              <span><a class="tipOption <?php echo $activeClass; ?>" data-price="<?php echo $store_delivery_boy_tip ?>">+<?php echo $store_delivery_boy_tip ?></a></span> 
                              <?php
                           }
                        }
                        ?>                        
                     </div>
                     <?php
                  }
               ?>
               
               <ul class="cart-lists">
                  <?php 
                  $tooltipTitle = 'Sub Total ';
                  $discount = 0;
                  if ($couponType == 'deal' && $voucher) {
                    if (in_array($voucher->deal_type, ['FOD','POD','DOD']) && $voucher->discount > 0) {
                      $tooltipTitle .= ' - promo code';
                      $discount = ($subTotal*$voucher->discount/100);
                      if ($discount > $voucher->max_discount && $voucher->max_discount > 0) {
                        $discount = $voucher->max_discount;
                      }
                      $subTotal = $subTotal - $discount;
                    } 
                  } else {
                    if ($voucher && $voucher->discount > 0) {
                      $tooltipTitle .= ' - promo code';
                      if ($voucher->discount_type == 'Percentage') {
                        $discount = ($subTotal*$voucher->discount/100);
                        if ($discount > $voucher->max_discount && $voucher->max_discount > 0) {
                          $discount = $voucher->max_discount;
                        }
                        $subTotal = $subTotal - $discount;
                      } else {
                        $discount = $voucher->discount;
                        if ($discount > $voucher->max_discount) {
                          $discount = $voucher->max_discount;
                        }
                        $subTotal = $subTotal - $discount;
                      }                        
                    }
                  }
                  
                  $subTotal += $catVDExpertPrice;
                  if ($store->store_enable_sur_charge == 'yes') {
                    $surgeCharges = \App\StoresSurgeCharges::where('store_id', $store_id)->where('date', date('Y-m-d', strtotime($order_date)))->get()->first();
                    if ($surgeCharges) {
                      $subTotal = $subTotal+($subTotal*$surgeCharges->percentage/100);
                      $tooltipTitle .= ' + Surge Charges';
                      if ($discount > 0) {                        
                        ?>
                        <li>Sub Total<b>Incl. surge <?php echo $surgeCharges->percentage ?>%<span style="float: none;" data-toggle="tooltip" data-placement="top" title="<?php echo $surgeCharges->reason ?>"><i class="fa fa-info-circle" aria-hidden="true" style="color: #fe6107;"></i></span></b>  <span><?php echo priceFormat($subTotal + $discount) ?></span> </li>
                        <?php 
                      } else {                        
                        ?>
                        <li>Sub Total<b>Incl. surge <?php echo $surgeCharges->percentage ?>%<span style="float: none;" data-toggle="tooltip" data-placement="top" title="<?php echo $surgeCharges->reason ?>"><i class="fa fa-info-circle" aria-hidden="true" style="color: #fe6107;"></i></span></b>  <span><?php echo priceFormat($subTotal) ?></span> </li>
                        <?php 
                      }
                    } else {
                      $tooltipTitle .= 'Sub Total';
                      if ($discount > 0) {
                        ?>
                        <li>Sub Total <span><?php echo priceFormat($subTotal + $discount) ?></span> </li>
                        <?php   
                      } else {
                        ?>
                        <li>Sub Total <span><?php echo priceFormat($subTotal) ?></span> </li>
                        <?php 
                      }
                       
                    }
                  } else {
                    $tooltipTitle .= 'Sub Total';
                     ?>
                     <li>Sub Total <span><?php echo priceFormat($subTotal) ?></span> </li>
                     <?php 
                  }
                  if ($discount) {
                    ?>  
                    <li class="discountInput">Discount <span><?php echo priceFormat($discount) ?></span> </li>
                    <!-- <li>Total <span><?php echo priceFormat($subTotal) ?></span> </li> -->
                    <?php 
                  }

                  if ($delivery_pickup_address['order_type'] == 'Delivery') {  
                    if ($couponType == 'deal' && $voucher && in_array($voucher->deal_type, ['FD'])) {
                      ?>
                         <li class="deliveryInput">Delivery <span><del><?php echo priceFormat($delivery_pickup_address['pickDeliveryPrice']) ?></del>$ 0.00</span> </li>
                      <?php
                    } else if ($voucher && $voucher->free_delivery == 1) {
                      ?>
                         <li class="deliveryInput">Delivery <span><del><?php echo priceFormat($delivery_pickup_address['pickDeliveryPrice']) ?></del>$ 0.00</span> </li>
                      <?php
                    } else {
                      $tooltipTitle .= ' + Delivery';
                      ?>
                         <li class="deliveryInput">Delivery <span><?php echo priceFormat($delivery_pickup_address['pickDeliveryPrice']) ?></span> </li>
                      <?php
                    }                                          
                  }
                  if ($subTotal < $minimumOrderPrice) {
                  ?>
                     <li class="extraChargesInput">Extra Charges<span><?php echo priceFormat($minimumOrderPrice-$subTotal) ?></span> </li>
                  <?php 
                     $subTotal += $minimumOrderPrice-$subTotal;
                     $tooltipTitle .= ' + Extra Charges';
                  }
                  if ($delivery_pickup_address['order_type'] == 'Delivery') {
                    if ($couponType == 'deal' && $voucher && in_array($voucher->deal_type, ['FD'])) {

                    } else if ($voucher && $voucher->free_delivery == 1) {
                      
                    } else {
                      $subTotal += $delivery_pickup_address['pickDeliveryPrice'];
                    }
                  }
                  if (isset($delivery_pickup_address['tipPrice'])) {
                    $tooltipTitle .= ' + Tip ';
                     ?>
                        <li class="tipInput">Tip <a class="removeTip btn-remove">-</a><span><?php echo priceFormat($delivery_pickup_address['tipPrice']) ?></span> </li>
                     <?php
                     $subTotal += $delivery_pickup_address['tipPrice'];
                  }
                  $total = $subTotal; ?>
                  <li class="totalInput">Total <span><?php echo priceFormat($total) ?></span> </li>
                  <?php 
                  $taxPrice = 0;
                  if ($store->store_enable_tax == 'yes' && !empty($store->store_tax)) {
                     $taxPrice = $total * $store->store_tax / 100;
                     ?>
                     <li class="taxesInput">Taxes <span style="float: none;" data-toggle="tooltip" data-placement="top" title="<?php echo $tooltipTitle ?>"><i class="fa fa-info-circle" aria-hidden="true" style="color: #fe6107;"></i></span><span><?php echo priceFormat($taxPrice) ?></span> </li>
                     <?php
                  }
                  ?>                  
                  <li class="col-orange grandTotal">Grand Total<b>(Incl. Tax)</b> <span><?php echo priceFormat($subTotal+$taxPrice) ?></span> </li>
               </ul>
            </div>
            <!-- close checkout sidebar-->
            <a class="payment-btn proceedOrder">Payment</a>
         </div>
      </div>
   </div>
   <?php $mainGrandTotal = ($subTotal+$taxPrice) ?>
   <section class="payment-page" style="display: none;">
	    <div class="payment-page-inner">
	         <div class="payment-header">
	            Choose Method
	         </div>
	         <div class="payment-block">
	           <?php 
	           $enableGetways = false;
	           if (isset($payment_getway['enable_stripe']) && $payment_getway['enable_stripe'] == 'Yes') {
	             $enableGetways = true;
	             ?>
	             <div class="paymnet-block-inner">
	             	<div class="radio radio-danger">
	                   <input type="radio" name="getway" id="getway-stripe" value="stripe" class="getwayCheck">
	                   <label for="getway-stripe">
	                   <span class="paymnet-img"><img src="<?php echo publicPath() ?>/front/images/strip_logo.png"></span>
	                   </label>
	                   <span class="size-amt"><img src="<?php echo publicPath() ?>/front/images/debit_logo.png"></span>
	                </div>
	                <div class="strip_element" style="display: none;">
   						<form id="checkoutForm">
		                	<p><input type="hidden" name="amount" value="<?php echo $mainGrandTotal ?>" /></p>
		                	<p><input type="hidden" name="email" id="stripEmail" value="" /></p>
	                		<div id="card-element">
	                	 	</div>
		                	<div id="card-errors" class="alert alert-warning" style="display: none;" role="alert"></div> 
		                	<button type="submit" style="display: none" class="stripSubmit"></button>
		                </form> 
	                </div>             
	             </div>
	             <?php
	           }
	           if (isset($payment_getway['enable_paypal']) && $payment_getway['enable_paypal'] == 'Yes') {
	             $enableGetways = true;
	             ?>
	             <div class="paymnet-block-inner">
	               <div class="radio radio-danger">
	                  <input type="radio" name="getway" id="getway-paypal" value="paypal" class="getwayCheck">
	                  <label for="getway-paypal">
	                  <span class="paymnet-img"><img src="<?php echo publicPath() ?>/front/images/paypal_logo.png"></span>
	                  </label>
	               </div>
	            </div>
	             <?php
	           }
	           $fpos_cod_max_order_amount = (isset($payment_getway['fpos_cod_max_order_amount'])?$payment_getway['fpos_cod_max_order_amount']:'');
	           if (isset($payment_getway['enable_eftpos']) && $payment_getway['enable_eftpos'] == 'Yes' && ($fpos_cod_max_order_amount && $fpos_cod_max_order_amount > $mainGrandTotal)) {
	             $enableGetways = true;
	             ?>
	             <div class="paymnet-block-inner">
	               <div class="radio radio-danger">
	                  <input type="radio" name="getway" id="getway-eftpos" value="eftpos" class="getwayCheck">
	                  <label for="getway-eftpos">
	                  <span class="paymnet-img">EFTPOS Machine</span>
	                  </label>
	               </div>
	            </div>
	             <?php
	           }
	           if (isset($payment_getway['enable_cod']) && $payment_getway['enable_cod'] == 'Yes' && ($fpos_cod_max_order_amount && $fpos_cod_max_order_amount > $mainGrandTotal)) {
	             $enableGetways = true;
	             ?>
	             <div class="paymnet-block-inner">
	               <div class="radio radio-danger">
	                  <input type="radio" name="getway" id="getway-cod" value="cod" class="getwayCheck">
	                  <label for="getway-cod">
	                  <span class="paymnet-img">Cash</span>
	                  </label>
	               </div>
	            </div>
	             <?php
	           }
	           ?>         
	         </div>
	         <?php 
	         if ($enableGetways == false) {
	           ?>
	           <p>No getway enable, Please contact site adminstrative for more information</p>
	           <?php
	         }
	          ?>
	         <div class="payment-footer">
	            <p>Total Amount: <?php echo priceFormat($mainGrandTotal) ?></p>
	            <?php 
	            if ($enableGetways == true) {
	              ?>
	              <a class="proceedOrderPayment">Pay</a>
	              <?php
	            } else {
	             ?>
	             <a onclick="alert('warning', 'No getway enable, Please contact site adminstrative for more information')">Pay</a>
	             <?php
	            }
	            ?>         
	         </div>
	    </div>
   </section>
   <div class="background-shadow">
     <img src="<?php echo publicPath() ?>/front/images/loader.gif">
   </div>
   <?php
   $stripe_key = '';
   if (isset($payment_getway['stripe_key']) && !empty($payment_getway['stripe_key'])) {
     $stripe_key = $payment_getway['stripe_key'];
   }
     ?>
   <style type="text/css">
       .background-shadow {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: #00000073;
          z-index: 999;
          display: none;
      }

      .background-shadow img {
          position: absolute;
          width: 100px;
          height: 100px;
          top: 0;
          left: 0;
          right: 0;
          bottom:0;
          margin: auto;
      }
      .StripeElement {
          box-sizing: border-box;
          
          height: 40px;
          
          padding: 10px 12px;
          
          border: 1px solid transparent;
          border-radius: 4px;
          background-color: white;
          
          box-shadow: 0 1px 3px 0 #e6ebf1;
          -webkit-transition: box-shadow 150ms ease;
          transition: box-shadow 150ms ease;
      }
        
      .StripeElement--focus {
          box-shadow: 0 1px 3px 0 #cfd7df;
      }
        
      .StripeElement--invalid {
          border-color: #fa755a;
      }
        
      .StripeElement--webkit-autofill {
          background-color: #fefde5 !important;
      }
   </style>
   <script src="https://js.stripe.com/v3/"></script>
    
     <script>
     var publishable_key = '<?php echo $stripe_key ?>';
     </script>
     <script type="text/javascript">
       var stripe = Stripe(publishable_key);
       var elements = stripe.elements();
       var style = {
         base: {
           color: '#32325d',
           fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
           fontSmoothing: 'antialiased',
           fontSize: '16px',
           '::placeholder': {
             color: '#aab7c4'
           }
         },
         invalid: {
           color: '#fa755a',
           iconColor: '#fa755a'
         }
       };      
       var card = elements.create('card', {style: style});
         
       card.mount('#card-element');      
       card.addEventListener('change', function(event) {
         if (event.error) {
           jQuery('#card-errors').text(event.error.message).fadeIn();
         } else {
           jQuery('#card-errors').text('').fadeOut();
         }
       });
       var form = document.getElementById('checkoutForm');
       form.addEventListener('submit', function(event) {
         event.preventDefault();
         jQuery('#card-errors').text('').fadeOut();
         stripe.createToken(card).then(function(result) {
           if (result.error) {
             jQuery('#card-errors').text(result.error.message).fadeIn();
           } else {
             stripeTokenHandler(result.token);
           }
         });
       });      
       function stripeTokenHandler(token) {
         window.location.href="<?php echo url('strip/payment') ?>/"+token.id;
       }

     </script>
     
     <script type="text/javascript">
       jQuery(document).ready(function ($) { 
         $('.proceedOrderPayment').click(function(event) {
           if ($('.getwayCheck:checked').length == 0) {
             window.alert('Please select payment getway to proceed order');
             return false;
           }           
           var getway = $('.getwayCheck:checked').val();
           if (getway == 'stripe') {
           		var email = $('#checkout-email').val();
           		$('#stripEmail').val(email)
           		$('.stripSubmit').click();
           } else if (getway == 'paypal') {
             window.location.href="<?php echo url('paywithpaypal/') ?>";
           } else {
             window.location.href="<?php echo url('proceed/payment/') ?>/"+getway;
           }        
         });
         $('.getwayCheck').change(function(event) {
         	var getway = $('.getwayCheck:checked').val();
         	if (getway == 'stripe') {
         		$('.strip_element').fadeIn();
         	} else {
         		$('.strip_element').fadeOut();
         	}
         });
         $('.background-shadow').fadeIn();
         setTimeout(function(){
            $.ajax({
              url: '<?php echo url('verify/add/to/cart') ?>',
              type: 'GET',
              async:false,
              cache:false,
            })
            .done(function(response) {
               if (response == 'error') {
                 window.location.reload();
                 return false;
               }
               $('.background-shadow').fadeOut();
            });
         }, 1000);
                  
       });
     </script><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Estore/StoreCheckout.blade.php ENDPATH**/ ?>
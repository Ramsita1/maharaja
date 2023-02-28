<div class="page-body">
   <?php echo Form::open(['route' => array('orders.update', $order->order_id), 'method' => 'put', 'class' => 'md-float-material']);
      $attributes = $order->attributes;
      $product_detail = $order->product_detail;
      $billing_address = $order->billing_address;
      $shipping_address = $order->shipping_address;
   ?>
      <div class="row">
         <div class="col-md-8">
            <div class="card">            
               <div class="card-block">
                  <h4>Order Details</h4>
                  <div class="">
                  <?php
                  if (is_array($shipping_address)) {
                      foreach ($shipping_address as $deliveryKey => $deliveryValue) {
                          if($deliveryKey == 'store')
                          {
                              $storeSlug = explode('-', $deliveryValue);
                              $store_id = end($storeSlug);
                              $store_title = \App\Stores::where('store_id', $store_id)->get()->pluck('store_title')->first();
                              $deliveryValue = $store_title;
                          } else if ($deliveryKey == 'order_date') {
                              $deliveryValue = dateFormat($deliveryValue);
                          } else if ($deliveryKey == 'order_time') {
                              $deliveryValue = date('h:i A', strtotime($deliveryValue));
                          }                               
                          ?>
                          <div class="input-group row">
                             <label class="col-form-label" for="name"><?php echo ucfirst(str_replace(['_','-'], ' ', $deliveryKey)); ?></label><br>
                             <input type="text" readonly="" class="form-control form-control-lg" value="<?php echo $deliveryValue; ?>">
                             <span class="md-line"></span>
                          </div>
                          <?php
                      }
                  }
                  ?>
                  </div>
               </div>
            </div>
            <div class="card">            
               <div class="card-block">
                  <h4>Product Details</h4>
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped" cellpadding="0" cellspacing="0" style="margin:auto; background: #fff; padding: 0 15px 20px;display: block;" >
                         <tr align="left">
                            <th width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">Image</th>
                            <th width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">Item</th>
                            <th width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">Price</th>
                            <th width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">Quantity</th>
                            <th width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">Subtotal</th>
                         </tr>
                         <?php 
                             $subTotal = 0; 
                         if (!empty($attributes) && is_array($attributes)) {
                             foreach ($attributes as $cartData) {
                                 $menuItem = \App\MenuItems::where('menu_item_id', $cartData['menu_item_id'])->where('store_id', $cartData['store_id'])->get()->first();
                                 $subTotal += $cartData['item_total_price'];
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
                                            $totalAttrButePrice += $attribute['attr_total_price'];
                                            $totalattr_price += $attribute['attr_price'];
                                            $subTotal += $attribute['attr_total_price'];
                                        }
                                    }
                                 }
                                 ?>                          
                                 <tr>
                                    <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">
                                       <?php echo $menuItem->item_name;if ($attributeName) {
                                       ?>
                                       <br>(<?php echo implode(', ', $attributeName) ?>)
                                       <?php
                                      } ?>
                                    </td>
                                    <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">
                                       <img style="width: 100px;" src="<?php echo publicPath().'/'.$menuItem->item_image ?>" alt="<?php echo $menuItem->item_name ?>" class="img-responsive"/>
                                    </td>
                                    <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;"><?php echo priceFormat($cartData['item_price']+$totalattr_price) ?></td>
                                    <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;"><?php echo $cartData['item_quantity'] ?></td>
                                    <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;"><?php echo priceFormat($cartData['item_total_price']+$totalAttrButePrice) ?></td>
                                 </tr>
                             <?php
                             }
                         }
                         ?>                        
                        <tfoot>
                           <tr>
                              <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">
                                 
                              </td>
                              <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">
                              </td>
                              <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;"></td>
                              <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">Total</td>
                              <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;"><?php echo priceFormat($subTotal) ?></td>
                           </tr>
                        </tfoot>                   
                     </table>
                  </div>
               </div>
            </div>
         </div> 
         <div class="col-md-4">  
            <div class="card">            
               <div class="card-block">
                  <div class="">          
                     <div class="input-group row">
                        <label class="col-form-label" for="payment_status">Payment Status</label><br>
                        <select required="" id="payment_status" class="form-control form-control-lg" name="payment_status">
                           <option value="">Select</option>
                           <?php 
                           $payment_status = ['pending','complete'];
                           foreach ($payment_status as $paymentStatus) {
                              echo '<option value="'.$paymentStatus.'" '.($order->payment_status == $paymentStatus ?'selected':'').'>'.$paymentStatus.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="order_status">Order Status</label><br>
                        <select id="order_status" class="form-control form-control-lg" name="order_status">
                           <option value="">Select</option>
                           <?php 
                           $order_status = ['pending','processing','accepted','complete','rejected','canceled'];
                           foreach ($order_status as $statusValue) {
                              echo '<option value="'.$statusValue.'" '.($order->order_status == $statusValue ?'selected':'').'>'.$statusValue.'</option>';
                           }
                           ?>
                        </select>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="assinged_to_driver">Order assigned to delivery boy</label><br>
                        <input type="checkbox" name="assinged_to_driver" id="assinged_to_driver" value="1" <?php echo ($order->assinged_to_driver == 1?'checked':'') ?>>
                     </div>
                     <div class="input-group row showDriverWindow" style="display: <?php echo ($order->assinged_to_driver == 1?'block':'none') ?>">
                        <label class="col-form-label" for="driver_id">Please select delivery boy</label><br>
                        <select id="driver_id" class="form-control form-control-lg" name="driver_id">
                           <option value="0">Select</option>
                           <?php                           
                           foreach ($users as $user) {
                              echo '<option value="'.$user->user_id.'" '.($order->driver_id == $user->user_id ?'selected':'').'>'.$user->name.'</option>';
                           }
                           ?>
                        </select>
                     </div>
                     <div class="input-group row">
                       <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Update</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>     
   </form>
</div>
<div id="styleSelector">
</div>
<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('#assinged_to_driver').change(function(event) {
      if ($('#assinged_to_driver').is(':checked')) {
        $('.showDriverWindow').fadeIn();
      } else {
        $('.showDriverWindow').fadeOut();
      }
    });
  });
</script>
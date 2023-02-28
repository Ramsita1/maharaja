<?php 
$payment_getway = getThemeOptions('payment_getway'); 
$header = getThemeOptions('header');
$orderDatas = maybe_decode($order->attributes);
$product_details = maybe_decode($order->product_detail);
$billing_address = maybe_decode($order->billing_address);
$shipping_address = maybe_decode($order->shipping_address);
?>
<section class="payment-page">
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
        if (isset($payment_getway['enable_eftpos']) && $payment_getway['enable_eftpos'] == 'Yes' && ($fpos_cod_max_order_amount && $fpos_cod_max_order_amount > $order->grand_total)) {
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
        if (isset($payment_getway['enable_cod']) && $payment_getway['enable_cod'] == 'Yes' && ($fpos_cod_max_order_amount && $fpos_cod_max_order_amount > $order->grand_total)) {
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
         <p>Order ID: <?php echo $order->transaction_id ?></p>
         <p>Total Amount: <?php echo priceFormat($order->grand_total) ?></p>
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
<script src="https://checkout.stripe.com/checkout.js"></script>
  <?php
  $stripe_key = '';
  if (isset($payment_getway['stripe_key']) && !empty($payment_getway['stripe_key'])) {
    $stripe_key = $payment_getway['stripe_key'];
  }
    ?>
  <script type="text/javascript">
    window.onbeforeunload = function() { return "Your work will be lost."; };
    function pay(amount, transaction_id) {
      $('#success-alert').fadeOut();
      var handler = StripeCheckout.configure({
        key: '<?php echo $stripe_key ?>', // your publisher key id
        locale: 'auto',
        name: "<?php echo $order->name ?>",
        email: "<?php echo $order->email ?>",
        description: "<?php echo (isset($billing_address['store'])?$billing_address['store']:'') ?>",
        panelLabel: "Pay",
        allowRememberMe: false,
        token: function (token) {
        	$('.background-shadow').fadeIn();
          $.ajax({
            url: '{{ url('complete/order') }}',
            method: 'post',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { 
              tokenId: token.id, 
              amount: amount, 
              transaction_id: transaction_id 
            },
            success: (response) => {
              var result = $.parseJSON(response);
              //alert(result.status, result.message);
              if (result.status == 'success') {
                window.location.href="<?php echo url('thank-you') ?>?transaction_id="+transaction_id;
              }
            },
            error: (error) => {
              alert('There is an error in processing.');
              return false;
            }
          })
        }
      });
      handler.open({
        name: '<?php echo $order->name ?>',
        description: '<?php echo (isset($billing_address['store'])?$billing_address['store']:'') ?>',
        amount: amount * 100
      });
      $('.background-shadow').fadeOut(14000);
    }
    jQuery(document).ready(function ($) { 
      $('.proceedOrderPayment').click(function(event) {
        if ($('.getwayCheck:checked').length == 0) {
          window.alert('Please select payment getway to proceed order');
          return false;
        }
        $('.background-shadow').fadeIn();
        var getway = $('.getwayCheck:checked').val();
        if (getway == 'stripe') {
          pay(<?php echo $order->grand_total; ?>, '<?php echo $order->transaction_id; ?>');
        } else if (getway == 'paypal') {
          window.location.href="<?php echo url('paywithpaypal/') ?>/"+<?php echo $order->transaction_id; ?>;
        } else {
          window.location.href="<?php echo url('proceed/payment/') ?>/"+getway+'/'+<?php echo $order->transaction_id; ?>;
        }        
      });
    });
  </script>
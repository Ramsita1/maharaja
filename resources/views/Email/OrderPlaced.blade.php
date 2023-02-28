<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Confirmation Email</title>
        
    <style type="text/css">
    body{
      margin:0;
      padding:0;
    }
    .main_info th, .main_info td{
        padding:8px;
        border: 1px solid #c3c3c3;
        border-collapse: collapse;
        color: #565656;
    }
    .main_info{
        border-collapse: collapse;
    }
    .details p{
    margin: 8px 0;
    color:#152c3b;
    }
    .details h2{
    color:#152c3b;
    }
    
    @media (max-width:600px){
    table.main_outter {
        width: 100% !important;
        padding: 0 15px !important;
    }
.details td{
  width:100% !important;
}   
    }
</style>
</head>
    <body>
        <?php $header = getThemeOptions('header'); ?>
        <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="background: #ececec;padding: 20px 0 40px;" >
           <tr>
                <td>
                 
                    <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                        <tr>
                            <td style="text-align:center;padding: 20px 0;">
                                <img src="<?php echo publicPath().'/'.$header['headerlogo'] ?>">
                            </td>
                        </tr>
                    </table>
                <table align="center" border="0"  height="100%" width="600px" class="main_outter" >
                 <tr>
                 <td>
                    <table border="0" cellpadding="0" cellspacing="0"  style="margin:auto;">
                        <tr>
                                <td align="center" valign="top" id="templatePreheader" style="padding:15px 15px;background: #f3da09;">
                                    <h1 style="color: #152c3b;">Thank you for your order</h1>
                                </td>
                        </tr>
                        <tr>    
                            <td align="left" valign="top" id="templateinner" style="padding:28px 15px 10px;background: #fff;">  
                                <p style="color: #152c3b;">Your order has been received and is now being processed. Your order details are shown below for your reference:</p>  
                                <h1 style="color: #152c3b;">Order: #<?php echo $order->order_id; ?></h1>
                            </td>
                        </tr>
                    </table>
                    <table class="main_info" cellpadding="0" cellspacing="0" style="margin:auto; background: #fff; padding: 0 15px 20px;display: block;" >
                        <tr align="left">
                           <th width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">Image</th>
                           <th width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">Item</th>
                           <th width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">Price</th>
                           <th width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">Quantity</th>
                           <th width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">Subtotal</th>
                        </tr>
                        <?php 
                            $subTotal = 0; 
                        if (!empty($order->attributes) && is_array($order->attributes)) {
                            foreach ($order->attributes as $cartData) {
                                $menuItem = \App\MenuItems::where('menu_item_id', $cartData['menu_item_id'])->where('store_id', $cartData['store_id'])->get()->first();
                                ?>                          
                                <tr>
                                   <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">
                                      <?php echo $menuItem->item_name ?>
                                   </td>
                                   <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;">
                                      <img src="<?php echo publicPath().'/'.$menuItem->item_image ?>" alt="<?php echo $menuItem->item_name ?>" class="img-responsive"/>
                                   </td>
                                   <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;"><?php echo priceFormat($cartData['item_price']) ?></td>
                                   <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;"><?php echo $cartData['item_quantity'] ?></td>
                                   <td width="300px" style="border: 1px solid #c3c3c3;border-collapse: collapse;"><?php echo priceFormat($cartData['item_total_price']) ?></td>
                                </tr>
                            <?php
                            }
                        }
                        ?>                        
                                                
                    </table>
                    <table class="details" style="padding:0 15px 30px; background:#fff;width:100%;">
                        <tr>
                            <td style="width:49%; display:inline-block;">
                                <h2>Customer Details</h2>
                            </td>
                            <td></td>
                        </tr>
                        <?php 
                        $billing_address = $order->billing_address;
                        $shipping_address = $order->shipping_address;
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
                                <tr style="width:49%; display:inline-block;">
                                    <th><?php echo ucfirst(str_replace(['_','-'], ' ', $deliveryKey)); ?></th>
                                    <td><?php echo $deliveryValue; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                    <table class="footer" style="margin:auto;">
                        <tr>
                            <td>
                                <h5 style="margin-bottom:0;">Need Support ?</h5>
                                <p style="margin-top:8px;">Feel free to email us if you have any questions, comments or suggestions. We'll be happy to resolve your issues.</p>
                            </td>
                            
                        </tr>
                    </table>
                    </td>
                    </tr>
                    </table>
                </td>
           </tr>
        </table>
    </body>
</html>    

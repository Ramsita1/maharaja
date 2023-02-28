<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect, Response;
use App\Stores;
use App\DeviceToken;
use App\MenuItems;
use App\MenuItemType;
use App\MenuItemsCategory;
use App\ProductOrder;
use App\PhoneOtpVerification;

class OrderController extends Controller
{
    public static function storeRules($request, $id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email',
            'phone' => 'required|numeric'
        ];
    }
    public function verifyPhoneNO(Request $request)
    {
        // echo "<pre>";
        // print_r(session()->all());
        // die();
        $phone = $request->input('phone');  
        if (!is_numeric($phone)) {
            $response = [
                'status' => 'warning',
                'message' => 'Phone is invalid.'
            ];
            echo json_encode($response);
            die;
        }
        if(empty(PhoneOtpVerification::where('phone', $request->input('phone'))->where('otp_status', 1)->where('otp_for', 'phone_number')->get()->first()))
        {
            $otp = phoneOtpSendVarification($request->input('phone'));
            $response = [
                'status' => 'warning',
                'showOtp' => true,
                'message' => 'Please verify your phone, Otp sent .'.$otp
            ];
            echo json_encode($response);
            die;
        }      
        $otp = phoneOtpSendVarification($phone);
        $response = [
            'status' => 'success',
            'message' => 'Otp verified.'
        ];
        echo json_encode($response);
        die;
    }
    public function reSubmitVerifyPhoneOtp(Request $request)
    {
        $phone = $request->input('phone');
        $otp = $request->input('otp');
        if (empty($phone)) {
            $response = [
                'status' => 'warning',
                'message' => 'Phone is required.'
            ];
            echo json_encode($response);
            die;
        }
        if (!is_numeric($phone)) {
            $response = [
                'status' => 'warning',
                'message' => 'Phone is invalid.'
            ];
            echo json_encode($response);
            die;
        }
        if (empty($otp)) {
            $response = [
                'status' => 'warning',
                'message' => 'Otp is required.'
            ];
            echo json_encode($response);
            die;
        }
        $otpCode = PhoneOtpVerification::where('phone', $phone)->where('otp_status', 0)->where('otp_code', $otp)->where('otp_for', 'phone_number')->get()->first();
        if (empty($otpCode)) {
            $response = [
                'status' => 'warning',
                'message' => 'Otp is invalid.'
            ];
            echo json_encode($response);
            die; 
        }
        PhoneOtpVerification::where('otp_id', $otpCode->otp_id)->update(['otp_status'=>1]);
        $response = [
            'status' => 'success',
            'message' => 'Otp verified.'
        ];
        echo json_encode($response);
        die;
    }
    public function processOrder(Request $request)
    {
        // echo "<pre>";
        // print_r(session()->all());
        // die();
        $validator = Validator::make($request->all(), self::storeRules($request));
        if ($validator->fails()) {
            $response = [
                'status' => 'warning',
                'message' => $validator->getMessageBag()->first(),
                'cartHtml' => ''
            ];
            echo json_encode($response);
            die;
        }
        if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL))
        {
            $response = [
                'status' => 'warning',
                'message' => "The email must be a valid email address.",
                'cartHtml' => ''
            ];
            echo json_encode($response);
            die;
        }
        if(empty(PhoneOtpVerification::where('phone', $request->input('phone'))->where('otp_status', 1)->where('otp_for', 'phone_number')->get()->first()))
        {
            $otp = phoneOtpSendVarification($request->input('phone'));
            $response = [
                'status' => 'warning',
                'showOtp' => true,
                'message' => 'Please verify your phone, Otp sent .'.$otp
            ];
            echo json_encode($response);
            die;
        }
        $delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
        $delivery_pickup_address['name'] = $request->input('name');
        $delivery_pickup_address['phone'] = $request->input('phone');
        $delivery_pickup_address['email'] = $request->input('email');
        $delivery_pickup_address['special_instructions'] = $request->input('special_instructions');
        $delivery_pickup_address['accpet_term_condition'] = $request->input('accpet_term_condition');
        Session::put('delivery_pickup_address', $delivery_pickup_address); 
        Session::save();
        $response = [
            'status' => 'success',
            'message' => ''
        ];
        echo json_encode($response);
        die;
    }
    public static function generateOrder()
    {
        $cartDatas = Session::get ( 'cartData' );
        $delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
        if (!empty($cartDatas) && !empty($delivery_pickup_address)) {
            unset($delivery_pickup_address['submit']);
            $delivery_pickup_address['special_instructions'] = $delivery_pickup_address['special_instructions'];
            $subTotal = 0; 
            $catVDExpertPrice = 0;
            $currentUser = getCurrentUser();
            $store_id = 0;
            $products = [];
            $couponCode = (isset($delivery_pickup_address['couponCode'])?$delivery_pickup_address['couponCode']:'');
            $couponType = (isset($delivery_pickup_address['couponType'])?$delivery_pickup_address['couponType']:'');
            $deal_id = (isset($delivery_pickup_address['deal_id'])?$delivery_pickup_address['deal_id']:'');
            if ($couponType == 'deal') {
                $voucher = \App\Deals::where('deal_id', $deal_id)->get()->first();
            } else {
                $voucher = \App\Vouchers::where('code', $couponCode)->get()->first();
            }
            foreach ($cartDatas as $cartData) {
                $store_id = $cartData['store_id'];
                $menuItem = \App\MenuItems::where('menu_item_id', $cartData['menu_item_id'])->where('store_id', $cartData['store_id'])->get()->first()->toArray();
                $products[] = $menuItem;
                $menuItem = (object)$menuItem;
                $cartData['menuItem'] = $products;
                                           
                if ($voucher && (($voucher->category_id && $menuItem->item_category == $voucher->category_id) || (isset($voucher->menu_item_id) && $voucher->menu_item_id && $voucher->menu_item_id == $menuItem->menu_item_id)) || $menuItem->is_non_discountAble == 1) {
                  $catVDExpertPrice += $cartData['item_total_price'];
                } else {
                  $subTotal += $cartData['item_total_price'];
                }
                
                if (isset($cartData['attributes'])) {
                    foreach ($cartData['attributes'] as $attribute) {
                        if ($attribute['attr_type'] == 'remove') {
                            $subTotal -= $attribute['attr_total_price'];
                            $price_type = '-';
                        } else{ 
                            if ($voucher && (($voucher->category_id && $menuItem->item_category == $voucher->category_id) || (isset($voucher->menu_item_id) && $voucher->menu_item_id && $voucher->menu_item_id == $menuItem->menu_item_id)) || $menuItem->is_non_discountAble == 1) {
                              $catVDExpertPrice += $attribute['attr_total_price'];
                            } else {
                              $subTotal += $attribute['attr_total_price'];
                            }
                        }
                    }
                }
            }
                        
            $discount = 0;
            if ($couponType == 'deal' && $voucher) {
                if (in_array($voucher->deal_type, ['FOD','POD','DOD']) && $voucher->discount > 0) {
                  $discount = ($subTotal*$voucher->discount/100);
                  if ($discount > $voucher->max_discount && $voucher->max_discount > 0) {
                    $discount = $voucher->max_discount;
                  }
                  $subTotal = $subTotal - $discount;
                } 
            } else {
                if ($voucher && $voucher->discount > 0) {
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
            $store = \App\Stores::where('store_id', $store_id)->get()->first();
            $random = rand(000000, 999999);
            $order = new ProductOrder();
            $order->user_id = ($currentUser->user_id?$currentUser->user_id:0);
            $order->store_id = $store_id;
            $order->name = $delivery_pickup_address['name'];
            $order->email = $delivery_pickup_address['phone'];
            $order->phone = $delivery_pickup_address['email'];
            $order->accpet_term_condition = ($delivery_pickup_address['accpet_term_condition']?1:0);
            $order->attributes = maybe_encode($cartDatas);
            $order->product_detail = maybe_encode($products);
            $order->sub_total = $subTotal;
            if ($couponType == 'deal') {
                $order->coupon = $couponCode;
            } else {
                $order->coupon = $deal_id;
            }
            
            $order->discount = $discount;
            $order->coupon_data = $voucher;
            $order->coupon_type = $couponType;
            if ($store->store_enable_sur_charge == 'yes' && !empty($store->store_sur_charges)) {
                $sur_charge = ($subTotal*$store->store_sur_charges/100);
                $subTotal = $subTotal+$sur_charge;
                $order->sur_charge = $sur_charge;
                $order->sub_total_with_surcharge = $subTotal;
            } else {
                $order->sur_charge = 0;
                $order->sub_total_with_surcharge = 0;
            }
            $minimumOrderPrice = (isset($delivery_pickup_address['minimumOrderPrice'])?$delivery_pickup_address['minimumOrderPrice']:0);
            if ($subTotal < $minimumOrderPrice) {
                $order->extra_charges = $minimumOrderPrice-$subTotal;
                $subTotal += $minimumOrderPrice-$subTotal;
            } else {
                $order->extra_charges = 0;
            }
            if ($delivery_pickup_address['order_type'] == 'Delivery') {
                if ($couponType == 'deal' && $voucher && in_array($voucher->deal_type, ['FD'])) {
                    $order->delivery_price = 0;
                } else if ($voucher && $voucher->free_delivery == 1) {
                    $order->delivery_price = 0;
                } else {
                    $order->delivery_price = $delivery_pickup_address['pickDeliveryPrice'];
                    $subTotal += $delivery_pickup_address['pickDeliveryPrice'];
                }
            } else {
                $order->delivery_price = 0;
            }
            if (isset($delivery_pickup_address['tipPrice'])) {
                $order->tip_price = $delivery_pickup_address['tipPrice'];
                $subTotal += $delivery_pickup_address['tipPrice'];
            }
            $order->total = $subTotal;
            if ($store->store_enable_tax == 'yes' && !empty($store->store_tax)) {
                $taxPrice = $subTotal * $store->store_tax / 100;
                $order->tax = $taxPrice;
                $subTotal += $taxPrice;
            } else {
                $order->tax = 0;
            }
            $order->grand_total = $subTotal;
            $order->billing_address = maybe_encode($delivery_pickup_address);
            $order->shipping_address = maybe_encode($delivery_pickup_address);
            $order->created_at = new DateTime;
            $order->updated_at = new DateTime;
            $order->save();
            $order->transaction_id = $random.$order->order_id;
            $order->save();   
            return [
                'transaction_id' => $order->transaction_id,
                'status' => 'success'
            ];
        } else {
            return [
                'status' => 'warning',
                'message' => 'Your session is expired please try again.'
            ];
        }
    }
    public function completeFposCod($getway = null)
    {
        $response = $this->generateOrder();
        if ($response['status'] == 'warning') {
            Session::flash('warning', $response['message']);
            return Redirect()->back();       
        }
        $transaction_id = $response['transaction_id'];
        $order = ProductOrder::where('transaction_id', $transaction_id)->get()->first();

        if (empty($order)) {
            Session::flash('warning', 'Something went wrong, Please try again. Your Payment will be refund soon.');
            return Redirect::back();
        }


        $store_id = $order->store_id;
        $store = \App\Stores::where('store_id', $store_id)->get()->first();
        $device_token = \App\DeviceToken::where('user_id', $store->user_id)->get();

        $tokens = [];
		foreach($device_token as $device_data){
			$tokens[] = $device_data->token;
		}

		$token_data = [];
		$token_data["title"] = 'You have received an order';
		$token_data["body"] = 'You have received an order';
		$token_data["device_token"] = $tokens;
        $token_data["order_id"] = $order->order_id;
        $token_data["name"] = $order->name;
        $token_data["grand_total"] = $order->grand_total;
        $billing_address = maybe_decode($order->billing_address);
        $token_data["order_type"] = $billing_address['order_type'];
        $token_data["pickup_when"] = $billing_address['pickup_when'];
        // echo '<pre>';
        // print_r($token_data);
        // die();
		$this->sendPushNotification($token_data);

       
        $order->payment_getway = $getway;
        $order->save();

        Session::forget('cartData');
        Session::forget('delivery_pickup_address');  
        Session::save();              

        $emailTo = $order->email;
        $emailSubject = 'Order Placed';

        $order->attributes = maybe_decode($order->attributes);
        $order->product_detail = maybe_decode($order->product_detail);
        $order->billing_address = maybe_decode($order->billing_address);
        $order->shipping_address = maybe_decode($order->shipping_address);

        $emailBody = view('Email.OrderPlaced', compact('order'));

        SendEmail($emailTo, $emailSubject, $emailBody, [], '', '', '', '');

        SendEmail(adminEmail(), $emailSubject, $emailBody, [], '', '', '', '');

        Session::flash('success', 'Your order placed successfully.');
        $url = url('thank-you').'?transaction_id='.$transaction_id;
        return Redirect($url);
    }

    public function sendPushNotification($data){
		$url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = env('FIREBASE_SERVER_KEY');
        $title = $data['title'];
        $orderId = $data['order_id'];
        $name = $data['name'];
        $grandTotal = $data['grand_total'];
        $pickupWhen = $data['pickup_when'];
        $orderType = $data['order_type'];
        $body = $data['body'];
        $notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' => '1');
        /* if(@$data['ntype']) {
            $data_type = array('type'=>$data['ntype']);
        } else {
            $data_type = array('type'=>"1");
        } */
        $data_type = array('orderId'=>$orderId, 'name'=>$name, 'grand-total' => $grandTotal, 'pickup_when' =>$pickupWhen, 'order_type' => $orderType);
        // $data_type = array('orderId'=>$orderId, 'name'=>$name, 'grand-total' => $grandTotal);

        $tokens = $data['device_token'];
        foreach($tokens as $token){
            $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high','data'=>$data_type);
            $json = json_encode($arrayToSend);
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key='. $serverKey;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
            $response = curl_exec($ch);
			//print_r($response);
            /* echo $response;
            if ($response === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
            } */
            curl_close($ch);
        }

	}




}

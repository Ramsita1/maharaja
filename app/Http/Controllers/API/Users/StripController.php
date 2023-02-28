<?php

namespace App\Http\Controllers\API\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect, Response;
use App\Stores;
use App\MenuItems;
use App\MenuItemType;
use App\MenuItemsCategory;
use App\ProductOrder;
use App\PhoneOtpVerification;
use Stripe;
use App\Http\Controllers\Front\OrderController;

class StripController extends Controller
{
    public function createPayment($token)
    {
        if (empty($token)) {
            // Session::flash('warning', 'Your payment failed, Please try again letter');
            // return Redirect()->back();
            return Response()->json(['status'=>'warning', 'message' => 'Your payment failed, Please try again later' ],401);
            
        }
        $payment_getway = getThemeOptions('payment_getway');
        if (isset($payment_getway['stripe_secret']) && !empty($payment_getway['stripe_secret'])) {
            Stripe::setApiKey($payment_getway['stripe_secret']);
            $response = OrderController::generateOrder();
            if ($response['status'] == 'warning') {
                // Session::flash('warning', $response['message']);
                // return Redirect()->back(); 
                return Response()->json(['status'=>'warning', 'message' => $response['message'] ],401);      
            }
            $transaction_id = $response['transaction_id'];

            $order = ProductOrder::where('transaction_id', $transaction_id)->get()->first();
            if (empty($order)) {
                // Session::flash('warning', 'Something went wrong, Please try again. Your Payment will be refund soon.');
                // return Redirect()->back();
                return Response()->json(['status'=>'warning', 'message' => 'Something went wrong, Please try again. Your Payment will be refund soon.' ],401);
            }
            $stripe = Stripe::charges()->create([
                'source' => $token,
                'currency' => 'USD',
                'amount' => $order->grand_total * 100
            ]);
            if ($stripe) {
                $order->payment_getway = 'strip';
                $order->payment_id = $stripe['id'];
                $order->payment_status = 'complete';
                $order->getway_raw = maybe_encode($stripe);
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

                // Session::flash('success', 'Your payment completed successfully. Please wait for store confirmation.');
                $url = url('thank-you').'?transaction_id='.$transaction_id;
                // return Redirect($url);
                return Response()->json(['status'=>'success', 'message' => 'Your order placed successfully.', 'response' => compact('url') ],200);
            } else {
                $order->payment_status = 'Decline';
                $order->save();
                // $response = [
                //     'status' => 'warning',
                //     'message' => 'Something went wrong, Please try again. Your Payment Diclined by server.'
                // ];
                // echo json_encode($response);
                // die;
                return Response()->json(['status'=>'warning', 'message' => 'Something went wrong, Please try again. Your Payment Diclined by server.' ],401);
            }  
        } else {
            // $response = [
            //     'status' => 'warning',
            //     'message' => 'Please set strip key.'
            // ];
            // echo json_encode($response);
            // die;
            return Response()->json(['status'=>'warning', 'message' => 'Please set strip key.' ],401);
        }        
    }    
}

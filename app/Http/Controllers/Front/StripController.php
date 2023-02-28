<?php

namespace App\Http\Controllers\Front;

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
            Session::flash('warning', 'Your payment failed, Please try again letter');
            return Redirect()->back();
        }
        $payment_getway = getThemeOptions('payment_getway');
        if (isset($payment_getway['stripe_secret']) && !empty($payment_getway['stripe_secret'])) {
            Stripe::setApiKey($payment_getway['stripe_secret']);
            $response = OrderController::generateOrder();
            if ($response['status'] == 'warning') {
                Session::flash('warning', $response['message']);
                return Redirect()->back();       
            }
            $transaction_id = $response['transaction_id'];

            $order = ProductOrder::where('transaction_id', $transaction_id)->get()->first();
            if (empty($order)) {
                Session::flash('warning', 'Something went wrong, Please try again. Your Payment will be refund soon.');
                return Redirect()->back();
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

                Session::flash('success', 'Your payment completed successfully. Please wait for store confirmation.');
                $url = url('thank-you').'?transaction_id='.$transaction_id;
                return Redirect($url);
            } else {
                $order->payment_status = 'Decline';
                $order->save();
                $response = [
                    'status' => 'warning',
                    'message' => 'Something went wrong, Please try again. Your Payment Diclined by server.'
                ];
                echo json_encode($response);
                die;
            }  
        } else {
            $response = [
                'status' => 'warning',
                'message' => 'Please set strip key.'
            ];
            echo json_encode($response);
            die;
        }        
    }    
}

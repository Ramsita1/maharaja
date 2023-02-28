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

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;

/** All Paypal Details class **/
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use App\Http\Controllers\Front\OrderController;

class PaypalController extends Controller
{
    private $_api_context;
    public function __construct()
    {
        $payment_getway = getThemeOptions('payment_getway');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            (isset($payment_getway['paypal_client_id'])?$payment_getway['paypal_client_id']:''),
            (isset($payment_getway['paypal_secret'])?$payment_getway['paypal_secret']:''))
        );
        $paypalSettings = array(
            'mode' => (isset($payment_getway['paypal_mode'])?$payment_getway['paypal_mode']:'sandbox'),
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path() . '/logs/paypal.log',
            'log.LogLevel' => 'ERROR'
        );
        $this->_api_context->setConfig($paypalSettings);
    }
    public function payWithpaypal()
    {
        $response = OrderController::generateOrder();
        if ($response['status'] == 'warning') {
            Session::flash('warning', $response['message']);
            return Redirect()->back();       
        }
        $transaction_id = $response['transaction_id'];

        $order = ProductOrder::where('transaction_id', $transaction_id)->get()->first();
        if (empty($order)) {
            \Session::flash('warning', 'Something went wrong, Please try again. Your Payment will be refund soon.');
            return Redirect::back();
        }
        $product_detail = maybe_decode($order->product_detail);
        $itemNames = [];
        if (is_array($product_detail) && !empty($product_detail)) {
            foreach ($product_detail as $item) {
                $itemNames[] = $item['item_name'];
            }
        }
        Session::forget('cartData');
        Session::forget('delivery_pickup_address');  
        Session::save(); 
        $itemNames = implode(', ', $itemNames);
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($itemNames)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($order->grand_total);
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($order->grand_total);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($itemNames);
        $redirect_urls = new RedirectUrls();
        $successUrl = url('payWithpaypalcallback/success').'?transaction_id='.$transaction_id;
        $cancelUrl = url('payWithpaypalcallback/cancel').'?transaction_id='.$transaction_id;
        $redirect_urls->setReturnUrl($successUrl)
            ->setCancelUrl($cancelUrl);
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::flash('warning', 'Something went wrong, Please try again. Your Payment will be refund soon.');
                return Redirect::back();
            } else {
                \Session::flash('warning', 'Some error occur, sorry for inconvenient');
                return Redirect::back();
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        Session::put('paypal_payment_id', $payment->getId());
        Session::put('transaction_id', $transaction_id);
        Session::save();
        if (isset($redirect_url)) {
            return Redirect::away($redirect_url);
        }
        \Session::flash('warning', 'Unknown error occurred');
        return Redirect::back();
    }

    public function payWithpaypalcallback(Request $request, $callbackStatus = null)
    {
        $payment_id = Session::get('paypal_payment_id');
        $transaction_id = Session::get('transaction_id');
        Session::forget('paypal_payment_id');
        Session::forget('transaction_id');
        if (empty($request->get('PayerID')) || empty($request->get('token')) || empty($transaction_id)) {
            \Session::flash('warning', 'Payment failed');
            return Redirect::to('/cancel');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {
            $order = ProductOrder::where('transaction_id', $transaction_id)->get()->first();
            $order->payment_getway = 'paypal';
            $order->payment_id = $payment_id;
            $order->getway_raw = maybe_encode($result);
            $order->payment_status = 'complete';
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
        \Session::flash('warning', 'Payment failed');
        return Redirect::to('/cancel');

    }
}

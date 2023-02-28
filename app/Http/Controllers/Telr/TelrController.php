<?php

namespace App\Http\Controllers\Telr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Posts;
use App\Comment;
use App\Option;
use App\Links;
use App\Terms;
use App\TermRelations;
use App\FeedBack;
use App\ProductOrder;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;

class TelrController extends Controller
{
  public function proccedOrder(Request $request, $transaction_id = null){
    $telr = Config::get('telr');
    $order = ProductOrder::where('transaction_id', $transaction_id)->get()->first();
    $attributes = maybe_decode($order->attributes);
    $product_detail = maybe_decode($order->product_detail);
    $billing_address = maybe_decode($order->billing_address);

    $shipping_address = maybe_decode($order->shipping_address);
    $price = $order->grand_total;

    $params = array(
      'ivp_method'  =>  $telr['create']['ivp_method'],
      'ivp_store'   =>  $telr['create']['ivp_store'],
      'ivp_authkey' =>  $telr['create']['ivp_authkey'],
      'ivp_cart'    => $transaction_id,  
      'ivp_test'    => $telr['test_mode'],
      'ivp_amount'  => $price,
      'ivp_currency'=> $telr['currency'],
      'ivp_desc'    => 'Book Service', 
      'return_auth' => url($telr['create']['return_auth'].'/'.$transaction_id),
      'return_can'  => url($telr['create']['return_can'].'/'.$transaction_id),
      'return_decl' => url($telr['create']['return_decl'].'/'.$transaction_id),
      'bill_fname' => $billing_address['name'],
      'bill_sname' => $billing_address['lname'],
      'bill_addr1' => $billing_address['street_address'],
      'bill_addr2' => $billing_address['street_address'],
      'bill_city' => $billing_address['city'],
      'bill_region' => $billing_address['state'],
      'bill_zip' => $billing_address['zipcode'],
      'bill_country' => $billing_address['country'],
      'bill_email' => $billing_address['email'],
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $telr['sale']['endpoint']);
    curl_setopt($ch, CURLOPT_POST, count($params));
    curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $results = curl_exec($ch);
    curl_close($ch);
    $results = json_decode($results,true);
    $ref= trim(isset($results['order']['ref'])?$results['order']['ref']:'');
    $url= trim(isset($results['order']['url'])?$results['order']['url']:'');
    if (empty($ref) || empty($url)) {
      $order->order_status = 'canceled';
      $order->payment_status = 'Canceled';
      $order->updated_at = new DateTime;
      $order->save();
      $siteUrl = siteUrl().'/cancel?order_id='.$transaction_id;
      return redirect($siteUrl);
    }else{
      return redirect($url);
    }
  }
  public function success($transaction_id){
    $order = ProductOrder::where('transaction_id', $transaction_id)->get()->first();
    $order->order_status = 'processing';
    $order->payment_status = 'complete';
    $order->updated_at = new DateTime;
    $order->save();

    $order->attributes = maybe_decode($order->attributes);
    $order->product_detail = maybe_decode($order->product_detail);
    $order->billing_address = maybe_decode($order->billing_address);
    $order->shipping_address = maybe_decode($order->shipping_address);

    $emailBody = view('Email.OrderPlaced', compact('order'));

    SendEmail(adminEmail(), 'New order on infiway', $emailBody, [], '', '', '', '');

    $siteUrl = siteUrl().'/thank-you?order_id='.$transaction_id;
    return redirect($siteUrl);
  }

  public function cancel($transaction_id){
    $status = 'canceled';
    $order = ProductOrder::where('transaction_id', $transaction_id)->get()->first();    
    $order->order_status = $status;
    $order->payment_status = 'Canceled';
    $order->updated_at = new DateTime;
    $order->save();

    $emailTo = User::where('user_id', $order->user_id)->get()->pluck('email')->first();

    $order->attributes = maybe_decode($order->attributes);
    $order->product_detail = maybe_decode($order->product_detail);
    $order->billing_address = maybe_decode($order->billing_address);
    $order->shipping_address = maybe_decode($order->shipping_address);
    $title = '';
    
    $status = ucfirst($order->order_status);
    $emailSubject = 'Order '.$status ;
    $title = 'Your order is '.$status;
    $message = 'Your order has been '.$status.'. Your order details are shown below for your reference';
    
    $emailBody = view('Email.OrderChangedEmail', compact('order', 'title', 'message'));

    SendEmail($emailTo, $emailSubject, $emailBody, [], '', '', '', '');
    $siteUrl = siteUrl().'/cancel?order_id='.$transaction_id;
    return redirect($siteUrl);
  }

  public function declined(Request $request, $transaction_id){
    $status = 'rejected';
    $order = ProductOrder::where('transaction_id', $transaction_id)->get()->first();
    $order->order_status = $status;
    $order->payment_status = 'Decline';
    $order->updated_at = new DateTime;
    $order->save();
    $emailTo = User::where('user_id', $order->user_id)->get()->pluck('email')->first();

    $order->attributes = maybe_decode($order->attributes);
    $order->product_detail = maybe_decode($order->product_detail);
    $order->billing_address = maybe_decode($order->billing_address);
    $order->shipping_address = maybe_decode($order->shipping_address);
    $title = '';
    
    $status = ucfirst($order->order_status);
    $emailSubject = 'Order '.$status ;
    $title = 'Your order is '.$status;
    $message = 'Your order has been '.$status.'. Your order details are shown below for your reference';
    
    $emailBody = view('Email.OrderChangedEmail', compact('order', 'title', 'message'));

    SendEmail($emailTo, $emailSubject, $emailBody, [], '', '', '', '');
    $siteUrl = siteUrl().'/cancel?order_id='.$transaction_id;
    return redirect($siteUrl);
  }
}

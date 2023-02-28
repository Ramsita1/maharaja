<?php

namespace App\Http\Controllers\API\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProductOrder;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\User;
use App\DeviceToken;
use App\Printer;
use App\PrinterMenuItem;

class OrdersController extends Controller
{
	public function getOrders(Request $request )
	{
		
		$currentUser = getApiCurrentUser();
		$orders = ProductOrder::where(function($query) use($currentUser,$request){
		        if ($currentUser->role != 'Admin') {
		            $query->where('store_id', $currentUser->store_id);
		        }
				if (!empty($request->input('order_status'))) {
		            $query->where('order_status', $request->input('order_status'));
		        }
			})->orderBy('order_id', 'asc')->paginate(pagination());

			$orders->appends(['order_status' => $request->input('order_status')])->links();

		foreach ($orders as &$order) {
			$order->billing_address = maybe_decode($order->billing_address);

			unset($order->shipping_address);
			unset($order->getway_raw);
			unset($order->attributes);
			unset($order->product_detail);
			//unset($order->billing_address);
			unset($order->coupon_data);
		}
		return Response()->json(['status'=>'success', 'message' => 'Orders', 'response' => compact('orders') ],200);
	}

	public function getSingleOrder($order_id)
	{
		// die("test");
		$itemQtys['itemQty'] = [];
		$siteUrl = siteUrl()."/"."public"."/";
		
		if (!$order_id) {
			return Response()->json(['status'=>'success', 'message' => 'Order ID is required', 'response' => [] ], 401);
		}
		$currentUser = getApiCurrentUser();
		$order = ProductOrder::where('order_id', $order_id)
			->where(function($query) use($currentUser){
		        if ($currentUser->role != 'Admin') {
		            $query->where('store_id', $currentUser->store_id);
		        }
		    })->get()->first()->toArray();
		if (!$order) {
			return Response()->json(['status'=>'success', 'message' => 'Order not found in our system', 'response' => [] ], 401);
		}
		$order['product_detail'] = maybe_decode($order['product_detail']);
		$order['product_detail'][0]['item_image'] = $siteUrl.$order['product_detail'][0]['item_image'];
		$order['billing_address'] = maybe_decode($order['billing_address']);
		$order['attributes'] = maybe_decode($order['attributes']);
		$order['coupon_data'] = json_decode($order['coupon_data']);
		unset($order['shipping_address']);
		unset($order['getway_raw']);
		//unset($order['attributes']);

		$attributes = $order['attributes'];

		$tmp_order = maybe_decode($order['product_detail']);


		
			
		
		$tmp_order = array_map(function($tmp2_order) use ($attributes) {
			foreach($attributes as $key => $value){
				if($tmp2_order['menu_item_id'] == $attributes[$key]['menu_item_id']){
					$tmp2_order['item_quantity'] = $attributes[$key]['item_quantity'];
					return $tmp2_order;
				}
			}
		},$tmp_order);

		$printers = Printer::select("printers.printer_name","printers.printer_ip_address","printer_menu_items.menu_item_id")->join("printer_menu_items","printer_menu_items.printer_id","=","printers.id")->where("store_id",$currentUser->store_id)->get()->toArray();

		foreach($tmp_order as $tmp ){
			$count = 0;
			foreach($printers as $key => $tmp_data){
				// if($tmp_data["menu_item_id"] == $tmp["item_category"]){
				// 	unset($tmp['item_discount']);
				// 	$printers[$key]["items"][] = $tmp;
				// }
				if($tmp_data["menu_item_id"] == $tmp["item_category"] ){
					unset($tmp['item_discount']);
					$printers[$key]["items"][] = $tmp;				
				}
				// else{
				// 	$printers[$key]["items"] = [null];
				// }
				
			}
		}


		// $printers = array_map(function($printer){
		// 	if(isset($printer["items"])){
		// 		return $printer;
		// 	}
		// },$printers);

		// $printers = array_filter($printers,function($printer){
		// 	if(isset($printer["items"])){
		// 		return $printer;
		// 	}
		// });


		$new_printers = [];
		foreach($printers as $key => $printer){
			if(isset($printer["items"])){
				$new_printers[] = $printer;
			}
		}
		// return $new_printers;
	
		$order['printers'] = $new_printers;
		
		// foreach($order['attributes'] as $key => $value){
		// 	$category_detail_arr = $order['attributes'][$key]['item_quantity'];
		// 	$tmp_order[] = $category_detail_arr;
		// }
			
		return Response()->json(['status'=>'success', 'message' => 'Order', 'response' => compact('order') ],200);
	}
	public function updateRules()
	{
		return [
		    'order_status' => 'required|in:pending,processing,accepted,complete,hold,ready,delivery,delivered',
		    'order_id' => 'required|numeric',
			'set_preparing_time' => 'required_if:order_status,==,processing',
			'set_remainder_time' => 'required_if:order_status,==,hold',
		];
	}
	public function updateOrderStatus(Request $request)
	{

		$siteUrl = siteUrl()."/"."public"."/";
		$validator = Validator::make($request->all(), self::updateRules());

		if($validator->fails()){
		    return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],401);
		}
		$currentUser = getApiCurrentUser();
		$order = ProductOrder::where('order_id', $request->input('order_id'))
			->where(function($query) use($currentUser){
		        if ($currentUser->role != 'Admin') {
		            $query->where('store_id', $currentUser->store_id);
		        }
		    })->get()->first();
		if (!$order) {
			return Response()->json(['status'=>'success', 'message' => 'Order not found in our system', 'response' => [] ], 401);
		}

		$order->order_status = $request->input('order_status');
		// if($request->order_status == "pending")
		if($request->order_status == "hold")
		{
			$order->put_on_hold = $request->input('set_remainder_time');
		}
		if($request->order_status == "processing")
		{
			$order->send_to_kitchen = $request->input('set_preparing_time');
		}
		$order->updated_at = new DateTime;
		$order->save();

		$order = $order->toArray();
		$emailTo = \App\User::where('user_id', $order['user_id'])->get()->pluck('email')->first();

		$order['attributes'] = maybe_decode($order['attributes']);
		$order['product_detail'] = maybe_decode($order['product_detail']);
		$order['product_detail'][0]['item_image'] = $siteUrl.$order['product_detail'][0]['item_image']; 
		$order['billing_address'] = maybe_decode($order['billing_address']);
		$order['shipping_address'] = maybe_decode($order['shipping_address']);
		$title = '';

		$status = ucfirst($order['order_status']);
		$emailSubject = 'Order '.$status ;
		$title = 'Your order is '.$status;
		$message = 'Your order has been '.$status.'. Your order details are shown below for your reference';

		$emailBody = view('Email.OrderChangedEmail', compact('order', 'title', 'message'));

		SendEmail($emailTo, $emailSubject, $emailBody, [], '', '', '', '');

		$order['coupon_data'] = json_decode($order['coupon_data']);
		unset($order['shipping_address']);
		unset($order['getway_raw']);
		unset($order['attributes']);
		
		//echo $order['user_id'];
		//die;

		//$device_token = DeviceToken::where('user_id',$order['user_id'])->get();
		/*
		$device_token = DeviceToken::where('user_id',$currentUser->user_id)->get();

		$tokens = [];
		foreach($device_token as $device_data){
			$tokens[] = $device_data->token;
		}

		$token_data = [];
		$token_data["title"] = $title;
		$token_data["body"] = $request->input('order_id');
		$token_data["device_token"] = $tokens;
		$this->sendPushNotification($token_data);*/

		return Response()->json(['status'=>'success', 'message' => 'Order status updated successfully', 'response' => compact('order') ],200);
	}
	public function getDeliveryBoy(Request $request)
	{
		$currentUser = getApiCurrentUser();
		$users = \App\User::where('store_id', $currentUser->store_id)->where('role', 'DeliveryBoy')->get();
		return Response()->json(['status'=>'success', 'message' => 'Delivery boy', 'response' => compact('users') ],200); 
	}
	public function testPushNotification(){
		$data = [];
		$data["title"] = "Test Title";
		$data["body"] = "Test body message";
		$token = "eEyfELlhSGGfIT6MCjLwp4:APA91bFmjxuLztdb6GoCC8WkbAVXbtl1r_PvGC4veHVzhYalBduQqPnh85HpB8sv006pvYtIH33Ri3svIJduu-ucA6ixVFVxem14IQdgTHq3YKWF-XOyhL_iZVcv5J5yqGE86A-uz8Sq";
		$data["device_token"] = [$token];
		$this->sendPushNotification($data);
	}
	public function sendPushNotification($data){
		$url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = env('FIREBASE_SERVER_KEY');
        $title = $data['title'];
        $body = $data['body'];
        $notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' => '1');
        if(@$data['ntype']) {
            $data_type = array('type'=>$data['ntype']);
        } else {
            $data_type = array('type'=>"1");
        }


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
			print_r($response);
            /* echo $response;
            if ($response === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
            } */
            curl_close($ch);
        }

	}


	public function updateDeliveryBoyRules()
	{
		return [
			'order_id' => 'required|exists:product_orders,order_id',
			'driver_id' => 'required'
		];
	}
	public function assignDeliveryBoy(Request $request)
	{
		$validator = Validator::make($request->all(), self::updateDeliveryBoyRules());
		if($validator->fails()){
		    return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],401);
		}

		$currentUser = getApiCurrentUser();
		$delivery_boy = \App\User::where('store_id', $currentUser->store_id)->where([['role', 'DeliveryBoy'],['user_id',$request->driver_id]])->first();

		if (!$delivery_boy) {
			return Response()->json(['status'=>'success', 'message' => 'Delivery boy does not exist', 'response' => [] ], 401);
		}

		$order_detail = ProductOrder::select('order_status','store_id','driver_id')->where('order_id',$request->order_id)->first();
		
		if($order_detail->driver_id != 0){
			return Response()->json(['status'=>'success', 'message' => 'Already assigned delivery boy', 'response' => compact('users') ],401);
		}

		if($order_detail->order_status == "ready")
		{
			ProductOrder::where('order_id',$request->order_id)->update(['driver_id'=>$request->driver_id,
			 'assinged_to_driver' => 1 ]);
			return Response()->json(['status'=>'success', 'message' => 'Delivery boy assigned successfully'],200);
		}
		else{

			return Response()->json(['status'=>'success', 'message' => 'Invalid order status', 'response' => compact('users') ],401);						
		}

	}

	public function updatePaymentMethod()
	{
		return [
		    'payment_status' => 'required|in:complete,Canceled,Decline',
		    'order_id' => 'required|numeric',
			'payment_type' => 'required_if:payment_status,==,complete'
		];
	}

	public function updatePaymentStatus(Request $request)
	{

		//$siteUrl = siteUrl()."/"."public"."/";
		$validator = Validator::make($request->all(), self::updatePaymentMethod());

		if($validator->fails()){
		    return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],401);
		}
		
		$currentUser = getApiCurrentUser();
		/* $order = ProductOrder::where('order_id', $request->input('order_id'))
			->where(function($query) use($currentUser){
		        if ($currentUser->role != 'Admin') {
		            $query->where('store_id', $currentUser->store_id);
		        }
		    })->get()->first(); */

		$order = ProductOrder::where('order_id', $request->input('order_id'))->get()->first();	 
		if (!$order) {
			return Response()->json(['status'=>'success', 'message' => 'Order not found in our system', 'response' => [] ], 401);
		}
		
		//$order->payment_status = $request->input('payment_status');
		
		if($order->order_status == 'delivery'){
			if($order->payment_status == 'pending'){
				if($request->input('payment_status') == 'complete'){
					$order->payment_getway = $request->input('payment_type');
				}
				$order->payment_status = $request->input('payment_status');
				$order->updated_at = new DateTime;
				$order->save();	
				return Response()->json(['status'=>'success', 'message' => 'Order deliver', 'response' => ["payment_status" => $request->input('payment_status')] ], 200);

			}else{
				return Response()->json(['status'=>'success', 'message' => 'Payment status already '.$order->payment_status, 'response' => ["payment_status" => $request->input('payment_status')] ], 401);
			}
			
		}
		else{
			return Response()->json(['status'=>'success', 'message' => 'Payment status already '.$order->payment_status, 'response' => ["payment_status" => $request->input('payment_status')] ], 401);
		}
		
	}
	
	public function getSingleOrderClone($order_id)
	{
		// die("test");
		$siteUrl = siteUrl()."/"."public"."/";
		
		if (!$order_id) {
			return Response()->json(['status'=>'success', 'message' => 'Order ID is required', 'response' => [] ], 401);
		}
		$currentUser = getApiCurrentUser();
		$order = ProductOrder::where('order_id', $order_id)
			->where(function($query) use($currentUser){
		        if ($currentUser->role != 'Admin') {
		            $query->where('store_id', $currentUser->store_id);
		        }
		    })->get()->first()->toArray();
			
		if (!$order) {
			return Response()->json(['status'=>'success', 'message' => 'Order not found in our system', 'response' => [] ], 401);
		}
		//$order['attributes'] = maybe_decode($order['attributes']);
		$order['product_detail'] = maybe_decode($order['product_detail']);
		$order['product_detail'][0]['item_image'] = $siteUrl.$order['product_detail'][0]['item_image'];
		$order['billing_address'] = maybe_decode($order['billing_address']);
		$order['attributes'] = maybe_decode($order['attributes']);
		$order['coupon_data'] = json_decode($order['coupon_data']);
		$tmp_order = maybe_decode($order['product_detail']);


		$attributes = $order['attributes'];

		$tmp_order = array_map(function($tmp2_order) use ($attributes) {
			foreach($attributes as $key => $value){
				if($tmp2_order['menu_item_id'] == $attributes[$key]['menu_item_id']){
					$tmp2_order['item_quantity'] = $attributes[$key]['item_quantity'];
					return $tmp2_order;
				}
			}
		},$tmp_order);

		$printers = Printer::select("printers.printer_name","printers.printer_ip_address","printer_menu_items.printer_id","printer_menu_items.item_id")->join("printer_menu_items","printer_menu_items.printer_id","=","printers.id")->where("store_id",$currentUser->store_id)->get()->toArray();
		//$printers = Printer::select("printers.printer_name","printers.printer_ip_address","printer_menu_items.menu_item_id")->join("printer_menu_items","printer_menu_items.printer_id","=","printers.id")->where("store_id",$currentUser->store_id)->get()->toArray();
		//$a= $printers->groupBy('id')->get();
		// echo "<pre>";
        // print_r($printers);
		// echo json_encode($printers);
        // die;
		 //echo $a;

		
		$result = [];
		foreach($tmp_order as $tmp ){
			$count = 0;
			foreach($printers as $key => $tmp_data){
				//echo $key;
				if($tmp_data["item_id"] == $tmp["menu_item_id"] ){
				//if($tmp_data["menu_item_id"] == $tmp["item_category"] ){
					unset($tmp['item_discount']);
					$tmp_data["items"][]=$tmp;
					//$printers[$key]["items"][] = $tmp;		
					//echo $tmp_data['printer_id'];
					 // echo json_encode($printers);
        // die;			
		             foreach ( $result as $key => $element ) {
			           if ( $tmp_data['printer_id'] == $element['printer_id'] ) {
				           $result[$key]["items"][]= $tmp;
				           $count=1;
			           }
		             }
		           if($count==0)
		             {
			           $result[] = $tmp_data;
		             }
				}
				// else{
				// 	$printers[$key]["items"][] = null;
				// }
				
			}
			
		}
        //echo json_encode($result);
		//die;
		
		// return $new_printers;
	
		$order['printers'] = $result;
		// $order['printers'] = $printers;

		$order['product_detail'] = maybe_decode($order['product_detail']);
		$order['product_detail'][0]['item_image'] = $siteUrl.$order['product_detail'][0]['item_image'];
		// $order['billing_address'] = maybe_decode($order['billing_address']);
		// $order['attributes'] = maybe_decode($order['attributes']);
		// $order['coupon_data'] = json_decode($order['coupon_data']);
		unset($order['shipping_address']);
		unset($order['getway_raw']);
		//unset($order['attributes']);
		return Response()->json(['status'=>'success', 'message' => 'Order', 'response' => compact('order') ],200);
	}


}

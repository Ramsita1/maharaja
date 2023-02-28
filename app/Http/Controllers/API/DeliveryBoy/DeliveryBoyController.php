<?php

namespace App\Http\Controllers\API\DeliveryBoy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProductOrder;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\User;
use App\Stores;

class DeliveryBoyController extends Controller
{
    public function deliveryBoyOrderList(Request $request)
	{
		$currentUser = getApiCurrentUser();
        // $driverOrders = ProductOrder::select('order_id')->where('driver_id',$currentUser->user_id )->get();

        // $driverOrders = ProductOrder::where('driver_id',$currentUser->user_id )->orderBy('order_id', 'asc')->paginate(pagination());

        $orders = ProductOrder::where(function($query) use($currentUser,$request){
            if ($currentUser->role != 'Admin') {
                $query->where('driver_id', $currentUser->user_id);
            }
            if (!empty($request->input('order_status'))) {
                $query->where('order_status', $request->input('order_status'));
            }
        })->orderBy('order_id', 'asc')->paginate(pagination());

        $orders->appends(['order_status' => $request->input('order_status')])->links();

        $testData = array();
        foreach($orders as $order) 
        {
            // echo "<pre>";
            // print_r($order);die();
            // $dOrders = ProductOrder::where(function($query) use($order,$request){
            //     $query->where('order_id', $order->order_id);
			// 	if (!empty($request->input('order_status'))) {
            //         $query->where('order_status', $request->input('order_status'));
		    //     }
			// })->orderBy('order_id', 'asc')->paginate(pagination()); 
            
            $order->store_location = \App\Stores::select('store_lat','store_lng')->where('store_id', $order->store_id)->get()->first();    

            // foreach ($dOrders as $order) {
                $order->billing_address = maybe_decode($order->billing_address);
    
                unset($order->shipping_address);
                unset($order->getway_raw);
                unset($order->attributes);
                unset($order->product_detail);
                //unset($order->billing_address);
                unset($order->coupon_data);
               // $testData[''] =$dOrders;
                //return Response()->json(['status'=>'success', 'message' => 'Orders', 'response' => compact('testData') ],200);
                // }
                $testData[] = $order;
            }
            return Response()->json(['status'=>'success', 'message' => 'Orders', 'response' => compact('orders') ],200);
            // print_r($testData); 
	} 
}
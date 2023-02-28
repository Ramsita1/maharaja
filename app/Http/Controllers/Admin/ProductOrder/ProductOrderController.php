<?php

namespace App\Http\Controllers\Admin\ProductOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\User;
use App\ProductOrder;

class ProductOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function index(Request $request)
    {
        $view = 'Admin.ProductOrder.Index';
        $currentUser = getCurrentUser();
        
        $orders = ProductOrder::where(function($query) use($currentUser){
                if ($currentUser->role != 'Admin') {
                    $query->where('store_id', $currentUser->store_id);
                }
            })->orderBy('order_id', 'DESC')->paginate(pagination());

        return view('Admin', compact('view','orders'));
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = ProductOrder::find($id);
        $order->attributes = maybe_decode($order->attributes);
        $order->product_detail = maybe_decode($order->product_detail);
        $order->billing_address = maybe_decode($order->billing_address);
        $order->shipping_address = maybe_decode($order->shipping_address);
        $currentUser = getCurrentUser();
        $users = \App\User::where('store_id', $currentUser->store_id)->where('role', 'DeliveryBoy')->get();
        $view = 'Admin.ProductOrder.Show';
        return view('Admin', compact('view','id','order','currentUser','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = ProductOrder::find($id);
        $order->order_status = $request->order_status;
        $order->payment_status = $request->payment_status;
        $order->assinged_to_driver = ($request->assinged_to_driver?1:0);
        $order->driver_id = $request->driver_id;
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

        Session::flash ( 'success', "Order Updated." );
        return Redirect::back();  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = ProductOrder::find($id);
        $order->delete();

        Session::flash ( 'success', "Order Deleted." );
        return Redirect::route('orders.index');   
    }
}

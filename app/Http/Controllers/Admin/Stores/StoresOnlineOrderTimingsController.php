<?php

namespace App\Http\Controllers\Admin\Stores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\StoreOnlineOrderTimings;

class StoresOnlineOrderTimingsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function storeRules()
    {
        $rules = [
              
        ];
        return $rules;
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), self::storeRules($request));
        if ($validator->fails()) {
            Session::flash ( 'warning', $validator->getMessageBag()->first() );
            return Redirect::back()->withInput($request->all());
        }

        $user_id = Auth::user()->user_id;

        $storeOrder = new StoreOnlineOrderTimings();
        $storeOrder->user_id = $user_id;
        $storeOrder->type = $request->input('type');
        $storeOrder->store_id = $request->input('store_id');
        $storeOrder->weekdays = maybe_encode($request->input('weekdays'));
        $storeOrder->created_at = new DateTime;
        $storeOrder->updated_at = new DateTime;
        $storeOrder->save();

        Session::flash ( 'success', "Store Order Timings Created." );
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $store = Stores::find($id);
        $view = 'Admin.Store.CreateEdit';
        return view('Admin', compact('view','store'));
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
        $validator = Validator::make($request->all(), self::storeRules($request));
        if ($validator->fails()) {
            Session::flash ( 'warning', $validator->getMessageBag()->first() );
            return Redirect::back()->withInput($request->all());
        }
        $user_id = Auth::user()->user_id;

        $storeOrder = StoreOnlineOrderTimings::find($id);
        $storeOrder->type = $request->input('type');
        $storeOrder->store_id = $request->input('store_id');
        $storeOrder->weekdays = maybe_encode($request->input('weekdays'));
        $storeOrder->created_at = new DateTime;
        $storeOrder->updated_at = new DateTime;
        $storeOrder->save();

        Session::flash ( 'success', "Store Order Timings Updated." );
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $store = StoreOnlineOrderTimings::find($id);
        $store->delete();

        Session::flash ( 'success', "Store Deleted." );
        return Redirect::back();
    }
}

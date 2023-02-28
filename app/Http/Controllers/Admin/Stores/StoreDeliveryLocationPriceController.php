<?php

namespace App\Http\Controllers\Admin\Stores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\StoreDeliveryLocationPrice;

class StoreDeliveryLocationPriceController extends Controller
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
            'suburb' => 'required',
            'city' => 'required',   
            'postal_code' => 'required',
            //'charges' => 'required|numeric',  
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

        $storePrice = new StoreDeliveryLocationPrice();
        $storePrice->user_id = $user_id;
        $storePrice->suburb = $request->input('suburb');
        $storePrice->city = $request->input('city');
        $storePrice->postal_code = $request->input('postal_code');
        $storePrice->minimum_delivery_charge = $request->input('minimum_delivery_charge');
        $storePrice->minimum_delivery_order = $request->input('minimum_delivery_order');
        $storePrice->store_delivery_partner_compensation = ($request->input('store_delivery_partner_compensation')?$request->input('store_delivery_partner_compensation'):0);
        $storePrice->store_delivery_partner_commission = ($request->input('store_delivery_partner_commission')?$request->input('store_delivery_partner_commission'):0);
        $storePrice->charges = $request->input('charges');
        $storePrice->store_id = $request->input('store_id');
        $storePrice->created_at = new DateTime;
        $storePrice->updated_at = new DateTime;
        $storePrice->save();

        Session::flash ( 'success', "Store Price Created." );
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

        $storePrice = StoreDeliveryLocationPrice::find($id);
        $storePrice->user_id = $user_id;
        $storePrice->suburb = $request->input('suburb');
        $storePrice->city = $request->input('city');
        $storePrice->postal_code = $request->input('postal_code');
        $storePrice->charges = $request->input('charges');
        $storePrice->minimum_delivery_charge = $request->input('minimum_delivery_charge');
        $storePrice->minimum_delivery_order = $request->input('minimum_delivery_order');
        $storePrice->store_delivery_partner_compensation = ($request->input('store_delivery_partner_compensation')?$request->input('store_delivery_partner_compensation'):0);
        $storePrice->store_delivery_partner_commission = ($request->input('store_delivery_partner_commission')?$request->input('store_delivery_partner_commission'):0);
        $storePrice->store_id = $request->input('store_id');
        $storePrice->created_at = new DateTime;
        $storePrice->updated_at = new DateTime;
        $storePrice->save();

        Session::flash ( 'success', "Store Price Updated." );
        $pageUrl = route('store.showStore').'?tab=StoreDeliveryLocationPrice';
        return redirect($pageUrl);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $storePrice = StoreDeliveryLocationPrice::find($id);
        $storePrice->delete();

        Session::flash ( 'success', "Store Price Deleted." );
        return Redirect::back();
    }
}

<?php

namespace App\Http\Controllers\Admin\Stores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\StorePickupLocations;

class StorePickupLocationsController extends Controller
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

        $storePickupLocation = new StorePickupLocations();
        $storePickupLocation->user_id = $user_id;
        $storePickupLocation->suburb = $request->input('suburb');
        $storePickupLocation->city = $request->input('city');
        $storePickupLocation->postal_code = $request->input('postal_code');
        $storePickupLocation->store_id = $request->input('store_id');
        $storePickupLocation->created_at = new DateTime;
        $storePickupLocation->updated_at = new DateTime;
        $storePickupLocation->save();

        Session::flash ( 'success', "Store Pickup Location Created." );
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

        $storePickupLocation = StorePickupLocations::find($id);
        $storePickupLocation->user_id = $user_id;
        $storePickupLocation->suburb = $request->input('suburb');
        $storePickupLocation->city = $request->input('city');
        $storePickupLocation->postal_code = $request->input('postal_code');
        $storePickupLocation->created_at = new DateTime;
        $storePickupLocation->updated_at = new DateTime;
        $storePickupLocation->save();

        Session::flash ( 'success', "Store Pickup Location Updated." );
        $pageUrl = route('store.showStore').'?tab=StorePickupLocations';
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
        $storePickupLocation = StorePickupLocations::find($id);
        $storePickupLocation->delete();

        Session::flash ( 'success', "Store Pickup Location Deleted." );
        return Redirect::back();
    }
}

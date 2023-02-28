<?php

namespace App\Http\Controllers\Admin\Stores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\Stores;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = Stores::select('stores.*','users.name as userName')
                    ->leftJoin('users','users.user_id','=','stores.user_id')
                    ->paginate(pagination());

        $view = 'Admin.Store.Index';
        return view('Admin', compact('view','stores'));
    }
    public function showStore()
    {
        $store_id = getCurrentUserByKey('store_id');
        $store = Stores::find($store_id);
        $view = 'Admin.Store.CreateEdit';
        $pageUrl = route('store.showStore');
        return view('Admin', compact('view','store','pageUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $store = new Stores();
        $view = 'Admin.Store.CreateEdit';
        $pageUrl = route('stores.create');
        return view('Admin', compact('view','store','pageUrl'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function storeRules()
    {
        $rules = [
            'store_title' => 'required|string|max:255',
            'store_status' => 'required|in:open,close',     
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
        if(!empty($request->input('store_location_email')) && !filter_var($request->input('store_location_email'), FILTER_VALIDATE_EMAIL))
        {
            Session::flash ( 'success', "The email must be a valid email address." );
            return Redirect::back()->withInput($request->all());
        }
        $store_title = $request->input('store_title');
        $postCount = Stores::where('store_title', $store_title)->get()->count();
        if ($postCount > 0) {
            $store_name = $store_title.' '.$postCount;
        }else{
            $store_name = $store_title;
        }
        $store_name = str_slug($store_name, '-');

        $user_id = Auth::user()->user_id;
        $store = new Stores();
        $store->store_title = $store_title;
        $store->store_name = $store_name;
        $store->user_id = $request->input('user_id');
        $store->store_content = $request->input('store_content');
        $store->store_status = $request->input('store_status');
        $store->store_extra_charges = ($request->input('store_extra_charges')?'yes':'no');
        $store->store_enable_tax = ($request->input('store_enable_tax')?'yes':'no');
        $store->store_enable_sur_charge = ($request->input('store_enable_sur_charge')?'yes':'no');
        $store->store_enable_tip = ($request->input('store_enable_tip')?'yes':'no');
        $store->store_tax = ($request->input('store_tax')?$request->input('store_tax'):0);
        $store->store_delivery_boy_tips = $request->input('store_delivery_boy_tips');
        $store->store_city = $request->input('store_city');
        $store->store_postalCode = $request->input('store_postalCode');
        $store->store_suburb = $request->input('store_suburb');
        $store->store_address = $request->input('store_address');
        $store->store_country = $request->input('store_country');
        $store->store_pickup_minOrder = $request->input('store_pickup_minOrder');
        $store->store_you_may_like_item_show_count = $request->input('store_you_may_like_item_show_count');
        $store->store_delivery_minOrder = 0;
        $store->store_food_type = $request->input('store_food_type');
        $store->store_location_phone = $request->input('store_location_phone');
        $store->store_location_email = $request->input('store_location_email');
        $store->store_menu_style = $request->input('store_menu_style');
        $store->media = $request->input('media');
        $store->created_by = $user_id;
        $store->updated_by = $user_id;
        $store->created_at = new DateTime;
        $store->updated_at = new DateTime;
        $store->save();

        Session::flash ( 'success', "Store Created." );
        return Redirect::back();
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
        $pageUrl = route('stores.edit', $store->store_id);
        return view('Admin', compact('view','store','pageUrl'));
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
        if(!empty($request->input('store_location_email')) && !filter_var($request->input('store_location_email'), FILTER_VALIDATE_EMAIL))
        {
            Session::flash ( 'success', "The email must be a valid email address." );
            return Redirect::back()->withInput($request->all());
        }
        $store_title = $request->input('store_title');

        $user_id = Auth::user()->user_id;
        $store = Stores::find($id);
        $store->store_title = $store_title;
        $store->user_id = $request->input('user_id');
        $store->store_content = $request->input('store_content');
        $store->store_status = $request->input('store_status');
        $store->store_extra_charges = ($request->input('store_extra_charges')?'yes':'no');
        $store->store_enable_tax = ($request->input('store_enable_tax')?'yes':'no');
        $store->store_enable_sur_charge = ($request->input('store_enable_sur_charge')?'yes':'no');
        $store->store_enable_tip = ($request->input('store_enable_tip')?'yes':'no');
        $store->store_tax = ($request->input('store_tax')?$request->input('store_tax'):0);
        $store->store_delivery_boy_tips = $request->input('store_delivery_boy_tips');
        $store->store_city = $request->input('store_city');
        $store->store_postalCode = $request->input('store_postalCode');
        $store->store_suburb = $request->input('store_suburb');
        $store->store_address = $request->input('store_address');
        $store->store_country = $request->input('store_country');
        $store->store_pickup_minOrder = $request->input('store_pickup_minOrder');
        $store->store_you_may_like_item_show_count = $request->input('store_you_may_like_item_show_count');
        $store->store_delivery_minOrder = 0;
        $store->store_food_type = $request->input('store_food_type');
        $store->store_location_phone = $request->input('store_location_phone');
        $store->store_location_email = $request->input('store_location_email');
        $store->store_menu_style = $request->input('store_menu_style');
        $store->media = $request->input('media');
        $store->created_by = $user_id;
        $store->updated_by = $user_id;
        $store->created_at = new DateTime;
        $store->updated_at = new DateTime;
        $store->save();

        Session::flash ( 'success', "Store Updated." );
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
        $store = Stores::find($id);
        $store->delete();

        Session::flash ( 'success', "Store Deleted." );
        return Redirect::route('stores.index');
    }
}

<?php

namespace App\Http\Controllers\Admin\Deals;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\Stores;
use App\MenuItems;
use App\Deals;
use App\MenuItemsCategory;

class DealsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deals = Deals::paginate(pagination());

        $view = 'Admin.Deals.Index';
        return view('Admin', compact('view','deals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $deals = new Deals();
        $view = 'Admin.Deals.CreateEdit';
        return view('Admin', compact('view','deals'));
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
            'deal_title' => 'required|string|max:255',
            'deal_description' => 'required',
            'deal_type' => 'required'   
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

        $deals = new Deals();
        $deals->store_id = $request->input('store_id');
        $deals->deal_title = $request->input('deal_title');
        $deals->deal_description = $request->input('deal_description');
        $deals->deal_type = $request->input('deal_type');
        $deals->discount = $request->input('discount');
        $deals->min_order = $request->input('min_order');
        $deals->max_discount = $request->input('max_discount');
        $deals->menu_item_id = $request->input('menu_item_id');
        $deals->category_id = $request->input('category_id');
        $deals->start_date = $request->input('start_date');
        $deals->end_date = $request->input('end_date');
        $deals->buy_item = $request->input('buy_item');
        $deals->buy_item_qnty = $request->input('buy_item_qnty');
        $deals->get_item = $request->input('get_item');
        $deals->get_item_qnty = $request->input('get_item_qnty');
        $deals->is_deal_auto_apply = ($request->input('is_deal_auto_apply')?$request->input('is_deal_auto_apply'):0);
        $deals->start_time = date('H:i:s', strtotime($request->input('start_time')));
        $deals->end_time = date('H:i:s', strtotime($request->input('end_time')));
        $deals->location = maybe_encode($request->input('location'));
        $deals->week_of_day = maybe_encode($request->input('week_of_day'));
        $deals->created_at = new DateTime;
        $deals->updated_at = new DateTime;
        $deals->save();

        Session::flash ( 'success', "Deals Created." );
        return Redirect::route('deals.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deals = Deals::find($id);
        $view = 'Admin.Deals.CreateEdit';
        return view('Admin', compact('view','deals'));
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

        $deals = Deals::find($id);
        $deals->store_id = $request->input('store_id');
        $deals->deal_title = $request->input('deal_title');
        $deals->deal_description = $request->input('deal_description');
        $deals->deal_type = $request->input('deal_type');
        $deals->discount = $request->input('discount');
        $deals->min_order = $request->input('min_order');
        $deals->max_discount = $request->input('max_discount');
        $deals->menu_item_id = $request->input('menu_item_id');
        $deals->category_id = $request->input('category_id');
        $deals->start_date = $request->input('start_date');
        $deals->end_date = $request->input('end_date');
        $deals->buy_item = $request->input('buy_item');
        $deals->buy_item_qnty = $request->input('buy_item_qnty');
        $deals->get_item = $request->input('get_item');
        $deals->get_item_qnty = $request->input('get_item_qnty');
        $deals->is_deal_auto_apply = ($request->input('is_deal_auto_apply')?$request->input('is_deal_auto_apply'):0);
        $deals->start_time = date('H:i:s', strtotime($request->input('start_time')));
        $deals->end_time = date('H:i:s', strtotime($request->input('end_time')));
        $deals->location = maybe_encode($request->input('location'));
        $deals->week_of_day = maybe_encode($request->input('week_of_day'));
        $deals->updated_at = new DateTime;
        $deals->save();

        Session::flash ( 'success', "Deals Updated." );
        return Redirect::route('deals.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deals = Deals::find($id);
        $deals->delete();

        Session::flash ( 'success', "Deals Deleted." );
        return Redirect::route('deals.index');
    }
    public function getStoreCategory(Request $request)
    {
        $store_id = $request->input('store_id');
        $menuHtml = '<option value="">Select</option>';
        $menuItems = \App\MenuItems::where('store_id', $store_id)->get();
        foreach ($menuItems as $menuItem) {
           $menuHtml .= '<option value="'.$menuItem->menu_item_id.'">'.$menuItem->item_name.'</option>';
        }
        $html = '<option value="">Select</option>';
        $itemCategories = \App\MenuItemsCategory::where('store_id', $store_id)->get();
        foreach ($itemCategories as $itemCategory) {
           $html .= '<option value="'.$itemCategory->item_cat_id.'">'.$itemCategory->cat_name.'</option>';
        }

        $locationHtml = '<option value="">Select</option>';
        $locations = \App\StoreDeliveryLocationPrice::where('store_id', $store_id)->get();
        foreach ($locations as $location) {
           $locationHtml .= '<option value="'.$location->store_delivery_location_id.'">'.$location->postal_code.' - '.$location->city.'</option>';
        }

        echo json_encode(compact('html','menuHtml','locationHtml'));
        die;
    }
}

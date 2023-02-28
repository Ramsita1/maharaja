<?php

namespace App\Http\Controllers\Admin\Vouchers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\Stores;
use App\MenuItems;
use App\Vouchers;
use App\MenuItemsCategory;

class VouchersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vouchers = Vouchers::paginate(pagination());

        $view = 'Admin.Vouchers.Index';
        return view('Admin', compact('view','vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $voucher = new Vouchers();
        $view = 'Admin.Vouchers.CreateEdit';
        return view('Admin', compact('view','voucher'));
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
            'code' => 'required|string|max:255',
            'description' => 'required',
            'discount_type' => 'required',       
            'discount' => 'required',     
            'min_order' => 'required',     
            'usage_for' => 'required',     
            'start_date' => 'required',     
            'expiry_date' => 'required',    
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

        $voucher = new Vouchers();
        $voucher->code = $request->input('code');
        $voucher->description = $request->input('description');
        $voucher->discount_type = $request->input('discount_type');
        $voucher->discount = $request->input('discount');
        $voucher->max_discount = $request->input('max_discount');
        $voucher->store_id = $request->input('store_id');
        $voucher->min_order = $request->input('min_order');
        $voucher->usage_for = $request->input('usage_for');
        $voucher->category_id = $request->input('category_id');
        $voucher->expiry_date = $request->input('expiry_date');
        $voucher->start_date = $request->input('start_date');
        $voucher->start_time = date('H:i:s', strtotime($request->input('start_time')));
        $voucher->expiry_time = date('H:i:s', strtotime($request->input('expiry_time')));
        $voucher->usage_many = $request->input('usage_many');
        $voucher->user_tags = maybe_encode($request->input('user_tags'));
        $voucher->location = maybe_encode($request->input('location'));
        $voucher->week_of_day = maybe_encode($request->input('week_of_day'));
        $voucher->free_delivery = $request->input('free_delivery');
        $voucher->usage_many_multiple = $request->input('usage_many_multiple');
        $voucher->created_at = new DateTime;
        $voucher->updated_at = new DateTime;
        $voucher->save();

        Session::flash ( 'success', "Voucher Created." );
        return Redirect::route('vouchers.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $voucher = Vouchers::find($id);
        $view = 'Admin.Vouchers.CreateEdit';
        return view('Admin', compact('view','voucher'));
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
        $voucher = Vouchers::find($id);
        $voucher->code = $request->input('code');
        $voucher->description = $request->input('description');
        $voucher->discount_type = $request->input('discount_type');
        $voucher->discount = $request->input('discount');
        $voucher->max_discount = $request->input('max_discount');
        $voucher->store_id = $request->input('store_id');
        $voucher->min_order = $request->input('min_order');
        $voucher->usage_for = $request->input('usage_for');
        $voucher->category_id = $request->input('category_id');
        $voucher->expiry_date = $request->input('expiry_date');
        $voucher->start_date = $request->input('start_date');
        $voucher->start_time = date('H:i:s', strtotime($request->input('start_time')));
        $voucher->expiry_time = date('H:i:s', strtotime($request->input('expiry_time')));
        $voucher->usage_many = $request->input('usage_many');
        $voucher->user_tags = maybe_encode($request->input('user_tags'));
        $voucher->location = maybe_encode($request->input('location'));
        $voucher->week_of_day = maybe_encode($request->input('week_of_day'));
        $voucher->free_delivery = $request->input('free_delivery');
        $voucher->usage_many_multiple = $request->input('usage_many_multiple');
        $voucher->updated_at = new DateTime;
        $voucher->save();

        Session::flash ( 'success', "Voucher Updated." );
        return Redirect::route('vouchers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $voucher = Vouchers::find($id);
        $voucher->delete();

        Session::flash ( 'success', "Voucher Deleted." );
        return Redirect::route('vouchers.index');
    }
}

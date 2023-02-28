<?php

namespace App\Http\Controllers\Admin\Stores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\StoresSurgeCharges;
use App\Stores;

class StoresSurgeChargesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store_id = Auth::user()->store_id;
        $surgeCharges = StoresSurgeCharges::where('store_id', $store_id)->orderby('store_surge_id', 'DESC')->paginate(pagination());
        $store = Stores::find($store_id);
        $view = 'Admin.StoresSurgeCharges.Index';
        return view('Admin', compact('view','surgeCharges','store'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $surgeCharges = new StoresSurgeCharges();

        $view = 'Admin.StoresSurgeCharges.CreateEdit';
        return view('Admin', compact('view','surgeCharges'));
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
            'date' => 'required', 
            'reason' => 'required',
            'percentage' => 'required' 
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
        
        $store_id = Auth::user()->store_id;
        $surgeCharges = new StoresSurgeCharges();
        $surgeCharges->store_id = $store_id;
        $surgeCharges->date = $request->input('date');
        $surgeCharges->reason = $request->input('reason');
        $surgeCharges->percentage = $request->input('percentage');
        $surgeCharges->status = 1;
        $surgeCharges->created_at = new DateTime;
        $surgeCharges->updated_at = new DateTime;
        $surgeCharges->save();

        Session::flash ( 'success', "Holiday Created." );
        return Redirect::route('storesSurgeCharges.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $surgeCharges = StoresSurgeCharges::find($id);
        $view = 'Admin.StoresSurgeCharges.CreateEdit';
        return view('Admin', compact('view','surgeCharges'));
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

        $surgeCharges = StoresSurgeCharges::find($id);
        $surgeCharges->date = $request->input('date');
        $surgeCharges->reason = $request->input('reason');
        $surgeCharges->percentage = $request->input('percentage');
        $surgeCharges->status = 1;
        $surgeCharges->created_at = new DateTime;
        $surgeCharges->updated_at = new DateTime;
        $surgeCharges->save();

        Session::flash ( 'success', "Holiday Updated." );
        return Redirect::route('storesSurgeCharges.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $surgeCharges = StoresSurgeCharges::find($id);
        $surgeCharges->delete();

        Session::flash ( 'success', "Holiday Deleted." );
        return Redirect::route('storesSurgeCharges.index');
    }
    public function updateSurcharge(Request $request)
    {
        $enableStoreSurCharge = $request->input('enableStoreSurCharge');
        $store_id = Auth::user()->store_id;
        $store = Stores::find($store_id);
        $store->store_enable_sur_charge = $enableStoreSurCharge;
        $store->save();
        echo 'Completed';
        die;
    }
}

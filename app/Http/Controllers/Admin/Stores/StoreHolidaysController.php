<?php

namespace App\Http\Controllers\Admin\Stores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\StoresHolidays;

class StoreHolidaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store_id = Auth::user()->store_id;
        $holidays = StoresHolidays::where('store_id', $store_id)->orderby('store_holiday_id', 'DESC')->paginate(pagination());

        $view = 'Admin.StoresHolidays.Index';
        return view('Admin', compact('view','holidays'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $holiday = new StoresHolidays();

        $view = 'Admin.StoresHolidays.CreateEdit';
        return view('Admin', compact('view','holiday'));
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
            'close_start_time' => 'required',
            'close_end_time' => 'required' 
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
        $holiday = new StoresHolidays();
        $holiday->store_id = $store_id;
        $holiday->date = $request->input('date');
        $holiday->close_start_time = date('H:i:s', strtotime($request->input('close_start_time')));
        $holiday->close_end_time = date('H:i:s', strtotime($request->input('close_end_time')));
        $holiday->status = 1;
        $holiday->full_day_off = ($request->input('full_day_off')?$request->input('full_day_off'):0);
        $holiday->created_at = new DateTime;
        $holiday->updated_at = new DateTime;
        $holiday->save();

        Session::flash ( 'success', "Holiday Created." );
        return Redirect::route('storeHolidays.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $holiday = StoresHolidays::find($id);
        $view = 'Admin.StoresHolidays.CreateEdit';
        return view('Admin', compact('view','holiday'));
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

        $holiday = StoresHolidays::find($id);
        $holiday->date = $request->input('date');
        $holiday->close_start_time = date('H:i:s', strtotime($request->input('close_start_time')));
        $holiday->close_end_time = date('H:i:s', strtotime($request->input('close_end_time')));
        $holiday->status = 1;
        $holiday->full_day_off = ($request->input('full_day_off')?$request->input('full_day_off'):0);
        $holiday->created_at = new DateTime;
        $holiday->updated_at = new DateTime;
        $holiday->save();

        Session::flash ( 'success', "Holiday Updated." );
        return Redirect::route('storeHolidays.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $holiday = StoresHolidays::find($id);
        $holiday->delete();

        Session::flash ( 'success', "Holiday Deleted." );
        return Redirect::route('storeHolidays.index');
    }
}

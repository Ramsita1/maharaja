<?php

namespace App\Http\Controllers\Admin\MenuItems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\Stores;
use App\MenuAttributeSize;

class MenuAttributeSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store_id = Auth::user()->store_id;
        $menuAttributeSizes = MenuAttributeSize::where('store_id', $store_id)->paginate(pagination());

        $view = 'Admin.MenuAttributeSize.Index';
        return view('Admin', compact('view','menuAttributeSizes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menuAttributeSize = new MenuAttributeSize();
        $view = 'Admin.MenuAttributeSize.CreateEdit';
        return view('Admin', compact('view','menuAttributeSize'));
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
            'size_name' => 'required|string|max:255',     
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
        $user = Auth::user();

        $menuAttributeSize = new MenuAttributeSize();
        $menuAttributeSize->store_id = $user->store_id;
        $menuAttributeSize->size_name = $request->input('size_name');
        $menuAttributeSize->created_at = new DateTime;
        $menuAttributeSize->updated_at = new DateTime;
        $menuAttributeSize->save();

        Session::flash ( 'success', "Menu Item Type Created." );
        return Redirect::route('menuAttributeSize.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menuAttributeSize = MenuAttributeSize::find($id);
        $view = 'Admin.MenuAttributeSize.CreateEdit';
        return view('Admin', compact('view','menuAttributeSize'));
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

        $menuAttributeSize = MenuAttributeSize::find($id);
        $menuAttributeSize->size_name = $request->input('size_name');
        $menuAttributeSize->updated_at = new DateTime;
        $menuAttributeSize->save();

        Session::flash ( 'success', "Menu Item Type Updated." );
        return Redirect::route('menuAttributeSize.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menuAttributeSize = MenuAttributeSize::find($id);
        $menuAttributeSize->delete();

        Session::flash ( 'success', "Menu Item Type Deleted." );
        return Redirect::route('menuAttributeSize.index');
    }
}

<?php

namespace App\Http\Controllers\Admin\MenuItems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\Stores;
use App\MenuItemType;

class MenuItemTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store_id = Auth::user()->store_id;
        $menuItemTypes = MenuItemType::where('store_id', $store_id)->paginate(pagination());

        $view = 'Admin.MenuItemType.Index';
        return view('Admin', compact('view','menuItemTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menuItemType = new MenuItemType();
        $view = 'Admin.MenuItemType.CreateEdit';
        return view('Admin', compact('view','menuItemType'));
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
            'type_name' => 'required|string|max:255',     
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

        $menuItemType = new MenuItemType();
        $menuItemType->store_id = $user->store_id;
        $menuItemType->type_name = $request->input('type_name');
        $menuItemType->type_description = $request->input('type_description');
        $menuItemType->created_by = $user->user_id;
        $menuItemType->updated_by = $user->user_id;
        $menuItemType->created_at = new DateTime;
        $menuItemType->updated_at = new DateTime;
        $menuItemType->save();

        Session::flash ( 'success', "Menu Item Type Created." );
        return Redirect::route('menuItemType.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menuItemType = MenuItemType::find($id);
        $view = 'Admin.MenuItemType.CreateEdit';
        return view('Admin', compact('view','menuItemType'));
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

        $menuItemType = MenuItemType::find($id);
        $menuItemType->type_name = $request->input('type_name');
        $menuItemType->type_description = $request->input('type_description');
        $menuItemType->updated_by = $user_id;
        $menuItemType->updated_at = new DateTime;
        $menuItemType->save();

        Session::flash ( 'success', "Menu Item Type Updated." );
        return Redirect::route('menuItemType.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menuItemType = MenuItemType::find($id);
        $menuItemType->delete();

        Session::flash ( 'success', "Menu Item Type Deleted." );
        return Redirect::route('menuItemType.index');
    }
}

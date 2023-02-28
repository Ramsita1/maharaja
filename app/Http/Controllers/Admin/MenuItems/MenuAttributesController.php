<?php

namespace App\Http\Controllers\Admin\MenuItems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\Stores;
use App\MenuAttributes;

class MenuAttributesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store_id = Auth::user()->store_id;
        $menuAttributes = MenuAttributes::where('store_id', $store_id)->paginate(pagination());

        $view = 'Admin.MenuAttributes.Index';
        return view('Admin', compact('view','menuAttributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menuAttribute = new MenuAttributes();
        $menuAttribute->attr_selection = 'single';
        $menuAttribute->attr_type = 'add';

        $view = 'Admin.MenuAttributes.CreateEdit';
        return view('Admin', compact('view','menuAttribute'));
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
            'attr_name' => 'required|string|max:255', 
            'attr_status' => 'required',
            'attr_selection' => 'required' 
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
        $attr_name = $request->input('attr_name');
        $menuAttribute = new MenuAttributes();
        $menuAttribute->store_id = $store_id;
        $menuAttribute->attr_name = $attr_name;
        $menuAttribute->attr_status = $request->input('attr_status');
        $menuAttribute->attr_selection = $request->input('attr_selection');
        $menuAttribute->attr_selection_mutli_value_min = ($request->input('attr_selection_mutli_value_min')?$request->input('attr_selection_mutli_value_min'):0);
        $menuAttribute->attr_selection_mutli_value_max = ($request->input('attr_selection_mutli_value_max')?$request->input('attr_selection_mutli_value_max'):0);
        $menuAttribute->attr_type = $request->input('attr_type');
        $menuAttribute->attr_main_choice = ($request->input('attr_main_choice')?$request->input('attr_main_choice'):0);
        $menuAttribute->attr_mandatory = ($request->input('attr_mandatory')?$request->input('attr_mandatory'):0);
        $menuAttribute->created_at = new DateTime;
        $menuAttribute->updated_at = new DateTime;
        $menuAttribute->save();

        Session::flash ( 'success', "Menu Attribute Created." );
        return Redirect::route('menuAttribute.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menuAttribute = MenuAttributes::find($id);
        if (!$menuAttribute->attr_selection) {
            $menuAttribute->attr_selection = 'single';
        }
        if (!$menuAttribute->attr_type) {
            $menuAttribute->attr_type = 'add';
        }
        $view = 'Admin.MenuAttributes.CreateEdit';
        return view('Admin', compact('view','menuAttribute'));
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

        $attr_name = $request->input('attr_name');
        $menuAttribute = MenuAttributes::find($id);
        $menuAttribute->attr_name = $attr_name;
        $menuAttribute->attr_status = $request->input('attr_status');
        $menuAttribute->attr_selection = $request->input('attr_selection');
        $menuAttribute->attr_selection_mutli_value_min = ($request->input('attr_selection_mutli_value_min')?$request->input('attr_selection_mutli_value_min'):0);
        $menuAttribute->attr_selection_mutli_value_max = ($request->input('attr_selection_mutli_value_max')?$request->input('attr_selection_mutli_value_max'):0);
        $menuAttribute->attr_type = $request->input('attr_type');
        $menuAttribute->attr_main_choice = ($request->input('attr_main_choice')?$request->input('attr_main_choice'):0);
        $menuAttribute->attr_mandatory = ($request->input('attr_mandatory')?$request->input('attr_mandatory'):0);
        $menuAttribute->created_at = new DateTime;
        $menuAttribute->updated_at = new DateTime;
        $menuAttribute->save();

        Session::flash ( 'success', "Menu Attribute Updated." );
        return Redirect::route('menuAttribute.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menuAttribute = MenuAttributes::find($id);
        $menuAttribute->delete();

        Session::flash ( 'success', "Menu Item Category Deleted." );
        return Redirect::route('menuAttribute.index');
    }
}

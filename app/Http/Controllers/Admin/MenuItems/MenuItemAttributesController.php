<?php

namespace App\Http\Controllers\Admin\MenuItems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\Stores;
use App\MenuItemAttributes;
use App\MenuAttributes;
use App\MenuAttributeSize;
use App\MenuItems;

class MenuItemAttributesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $store_id = Auth::user()->store_id;
        $menu_item_id = $request->get('menu_item_id');
        $item_attr_id = $request->get('item_attr_id');
        $menuItemAttributes = MenuItemAttributes::where('menu_item_id', $menu_item_id)
                        ->select('menu_item_attributes.*', DB::raw("(SELECT attr_name FROM menu_attributes WHERE menu_item_attributes.menu_attr_id = menu_attributes.menu_attr_id) as attribute"))
                        ->paginate(pagination());
        $attribute = MenuItemAttributes::find($item_attr_id);
        if (!$attribute) {
            $attribute = new MenuItemAttributes();
        }
        $menuAttributes = MenuAttributes::where('store_id', $store_id)->get();
        $menuAttributeSizes = MenuAttributeSize::where('store_id', $store_id)->get();
        $view = 'Admin.MenuItems.ManageAttributes';
        return view('Admin', compact('view','menuItemAttributes','menuAttributes','attribute','menuAttributeSizes'));
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

        if (
            $request->input('attr_default_choice') == 1 &&
            !empty(MenuItemAttributes::where('menu_item_id', $request->input('menu_item_id'))->where('attr_default_choice', 1)->get()->first())
        ) {
            Session::flash ( 'warning', 'This item already has default attribute' );
            return Redirect::back()->withInput($request->all());
        }
        /*$existCount = MenuItemAttributes::where('menu_attr_id', $request->input('menu_attr_id'))->where('menu_item_id', $request->input('menu_item_id'))->count();
        $menuMainAttribute = MenuAttributes::where('menu_attr_id', $request->input('menu_attr_id'))->get()->first();
        if ($menuMainAttribute->attr_selection == 'multiple' && $existCount >= $menuMainAttribute->attr_selection_mutli_value) {
            Session::flash ( 'warning', 'This attribute option has completed its choices' );
            return Redirect::back()->withInput($request->all());
        }*/

        $user_id = Auth::user()->user_id;
        $menuAttribute = new MenuItemAttributes();
        $menuAttribute->user_id = $user_id;
        $menuAttribute->menu_attr_id = $request->input('menu_attr_id');
        $menuAttribute->menu_item_id = $request->input('menu_item_id');
        $menuAttribute->attr_size = $request->input('attr_size');
        $menuAttribute->attr_name = $request->input('attr_name');
        $menuAttribute->attr_price = $request->input('attr_price');
        $menuAttribute->attr_desc = $request->input('attr_desc');
        $menuAttribute->attr_status = $request->input('attr_status');
        $menuAttribute->attr_default_choice = ($request->input('attr_default_choice')?$request->input('attr_default_choice'):0);
        $menuAttribute->created_at = new DateTime;
        $menuAttribute->updated_at = new DateTime;
        $menuAttribute->save();

        Session::flash ( 'success', "Menu Attribute Created." );
        $url = route('menuItemAttributes.index').'?menu_item_id='.$menuAttribute->menu_item_id;
        return Redirect($url);
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
        /*$existCount = MenuItemAttributes::where('menu_attr_id', $request->input('menu_attr_id'))->where('menu_item_id', $request->input('menu_item_id'))->where('item_attr_id', '!=', $id)->count();
        $menuMainAttribute = MenuAttributes::where('menu_attr_id', $request->input('menu_attr_id'))->get()->first();
        if ($menuMainAttribute->attr_selection == 'multiple' && $existCount >= $menuMainAttribute->attr_selection_mutli_value) {
            Session::flash ( 'warning', 'This attribute option has completed its choices' );
            return Redirect::back()->withInput($request->all());
        }*/
        if (
            $request->input('attr_default_choice') == 1 &&
            !empty(MenuItemAttributes::where('menu_item_id', $request->input('menu_item_id'))->where('attr_default_choice', 1)->where('item_attr_id', '!=', $id)->get()->first())
        ) {
            Session::flash ( 'warning', 'This item already has default attribute' );
            return Redirect::back()->withInput($request->all());
        }
        $user_id = Auth::user()->user_id;
        $menuAttribute = MenuItemAttributes::find($id);
        $menuAttribute->user_id = $user_id;
        $menuAttribute->menu_attr_id = $request->input('menu_attr_id');
        $menuAttribute->menu_item_id = $request->input('menu_item_id');
        $menuAttribute->attr_size = $request->input('attr_size');
        $menuAttribute->attr_name = $request->input('attr_name');
        $menuAttribute->attr_price = $request->input('attr_price');
        $menuAttribute->attr_desc = $request->input('attr_desc');
        $menuAttribute->attr_status = $request->input('attr_status');
        $menuAttribute->attr_default_choice = ($request->input('attr_default_choice')?$request->input('attr_default_choice'):0);
        $menuAttribute->created_at = new DateTime;
        $menuAttribute->updated_at = new DateTime;
        $menuAttribute->save();

        Session::flash ( 'success', "Menu Attribute Updated." );
        $url = route('menuItemAttributes.index').'?menu_item_id='.$menuAttribute->menu_item_id;
        return Redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menuAttribute = MenuItemAttributes::find($id);
        $menuAttribute->delete();

        Session::flash ( 'success', "Menu Item Category Deleted." );
        $url = route('menuItemAttributes.index').'?menu_item_id='.$menuAttribute->menu_item_id;
        return Redirect($url);
    }
    public function searchattribute(Request $request)
    {
        $keyword = $request->input('term');
        $rows = [];
        if (empty($keyword)) {
            $rows[] = [
                'value' => '',
                'data' => ''
            ];
        }
        $store_id = Auth::user()->store_id;
        $menuItemIDS = MenuItems::where('store_id', $store_id)->select('menu_item_id')->get();
        $attributes = MenuItemAttributes::where('attr_name', 'LIKE', '%'.$keyword.'%')->whereIn('menu_item_id', $menuItemIDS)->paginate(pagination());
        foreach ($attributes as $attribute) {
            $rows[] = [
                'id' => $attribute->item_attr_id,
                'value' => $attribute->attr_name.' '.$attribute->attr_price,
                'label' => $attribute->attr_name.' '.$attribute->attr_price,
                'data' => $attribute
            ];
        }
        return json_encode($rows);
    }
    public function searchMenuItem(Request $request)
    {        
        $menu_item_id = $request->input('menu_item_id');
        $keyword = $request->input('term');
        $rows = [];
        if (empty($keyword)) {
            $rows[] = [
                'value' => '',
                'data' => ''
            ];
        }
        $store_id = Auth::user()->store_id;
        $menuItems = MenuItems::where('store_id', $store_id)->where('item_name', 'LIKE', '%'.$keyword.'%')->where('menu_item_id', '!=', $menu_item_id)->where('item_is', 'Attributes')->paginate(pagination());
        foreach ($menuItems as $menuItem) {
            $rows[] = [
                'id' => $menuItem->menu_item_id,
                'value' => $menuItem->item_name,
                'label' => $menuItem->item_name
            ];
        }
        return json_encode($rows);
    }
    public function getItemAttributes(Request $request)
    {
        $itemID = $request->input('menu_item_id');
        $attributes = MenuItemAttributes::where('menu_item_id', $itemID)->get();
        $html = '<br><h6>Select attribute to copy.</h6>';
        foreach ($attributes as $attribute) {
            $html .= '<li class="list-group-item"><input type="checkbox" name="item_attr_ids[]" class="item_attr_ids" id="item_attr_ids_'.$attribute->item_attr_id.'" value="'.$attribute->item_attr_id.'"> &nbsp;<label for="item_attr_ids_'.$attribute->item_attr_id.'">'.$attribute->attr_name.' '.$attribute->attr_size.'</label></li>';
        }
        echo $html;
    }
    public function copyItemAttributes(Request $request)
    {
        $item_attr_ids = $request->input('item_attr_ids');
        $menu_item_id = $request->input('menu_item_id');
        $attributes = MenuItemAttributes::whereIn('item_attr_id', $item_attr_ids)->get();
        foreach ($attributes as $copyAttribute) {
            $menuAttribute = new MenuItemAttributes;
            $menuAttribute->user_id = $copyAttribute->user_id;
            $menuAttribute->menu_attr_id = $copyAttribute->menu_attr_id;
            $menuAttribute->menu_item_id = $menu_item_id;
            $menuAttribute->attr_size = $copyAttribute->attr_size;
            $menuAttribute->attr_name = $copyAttribute->attr_name;
            $menuAttribute->attr_price = $copyAttribute->attr_price;
            $menuAttribute->attr_desc = $copyAttribute->attr_desc;
            $menuAttribute->attr_status = $copyAttribute->attr_status;
            $menuAttribute->attr_default_choice = $copyAttribute->attr_default_choice;
            $menuAttribute->created_at = new DateTime;
            $menuAttribute->updated_at = new DateTime;
            $menuAttribute->save();
        }
        echo 'success';
        die;
    }
}

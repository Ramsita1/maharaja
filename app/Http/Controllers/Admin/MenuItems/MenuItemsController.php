<?php

namespace App\Http\Controllers\Admin\MenuItems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\Stores;
use App\MenuItems;
use App\MenuItemsCategory;
use App\MenuItemAttributes;
use App\Printer;
use App\PrinterMenuItem;

class MenuItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store_id = Auth::user()->store_id;
        $menuItems = MenuItems::where('store_id', $store_id)
            ->where(function($query){
                if (Request()->get('item_name')) {
                    $query->where('item_name', 'LIKE', '%'.Request()->get('item_name').'%');
                }
            })
            ->orderBy('menu_order', 'ASC')
            ->paginate(pagination());

        $view = 'Admin.MenuItems.Index';
        return view('Admin', compact('view','menuItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menuItem = new MenuItems();
        $menuItem->attributes = [];
        $view = 'Admin.MenuItems.CreateEdit';
        return view('Admin', compact('view','menuItem'));
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
            'item_name' => 'required|string|max:255',
            'item_price' => 'required|numeric',
            'item_status' => 'required|in:Active,Inactive',     
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

        $menuItem = new MenuItems();
        $menuItem->item_name = $request->input('item_name');
        $menuItem->store_id = $user->store_id;
        $menuItem->item_description = $request->input('item_description');
        $menuItem->item_image = $request->input('item_image');
        $menuItem->item_price = $request->input('item_price');
        if ($request->input('item_sale_price')) {
            $menuItem->item_sale_price = $request->input('item_sale_price');
        }
        if ($request->input('item_discount')) {
            $menuItem->item_discount = $request->input('item_discount');
        }
        if ($request->input('item_discount_start')) {
            $menuItem->item_discount_start = $request->input('item_discount_start');
        }
        if ($request->input('item_discount_end')) {
            $menuItem->item_discount_end = $request->input('item_discount_end');
        }
        $menuItem->item_category = $request->input('item_category');
        $menuItem->item_display_in = $request->input('item_display_in');
        $menuItem->item_for = json_encode($request->input('item_for'));
        $menuItem->item_is = $request->input('item_is');
        $menuItem->show_at_home = $request->input('show_at_home');
        $menuItem->is_delicous = $request->input('is_delicous');
        $menuItem->is_you_may_like = $request->input('is_you_may_like');
        $menuItem->item_status = $request->input('item_status');
        $menuItem->is_non_discountAble = ($request->input('is_non_discountAble')?1:0);
        $menuItem->created_by = $user->user_id;
        $menuItem->updated_by = $user->user_id;
        $menuItem->created_at = new DateTime;
        $menuItem->updated_at = new DateTime;
        $menuItem->save();

        self::insertUpdateAttributes($request, $menuItem);

        Session::flash ( 'success', "Menu Item Created." );
        return Redirect::route('menuItem.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menuItem = MenuItems::find($id);
        $menuItem->attributes = MenuItemAttributes::where('menu_item_id', $menuItem->menu_item_id)->get();
        $menuItem->item_for = json_decode($menuItem->item_for);
        $menuItem->printers = PrinterMenuItem::where('item_id', $id)->get();
        $view = 'Admin.MenuItems.CreateEdit';
        return view('Admin', compact('view','menuItem'));
    }
    public function insertDeletePrinter(Request $request)
    {
        if($request->input('action') == 1){
           $p = PrinterMenuItem::where('printer_id', $request->input('printerid'))->where('item_id',$request->input('menuItemid'))->delete();
        return 'deleted';
        }
    else{
        $pmi = new PrinterMenuItem();
        $pmi->printer_id = $request->input('printerid');
        $pmi->item_id = $request->input('menuItemid');
        $pmi->save();
        return 'inserted';
    }
        
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

        $menuItem = MenuItems::find($id);
        $menuItem->item_name = $request->input('item_name');
        $menuItem->item_description = $request->input('item_description');
        $menuItem->item_image = $request->input('item_image');
        $menuItem->item_price = $request->input('item_price');
        if ($request->input('item_sale_price')) {
            $menuItem->item_sale_price = $request->input('item_sale_price');
        }else{
            $menuItem->item_sale_price = 0;
        }
        if ($request->input('item_discount')) {
            $menuItem->item_discount = $request->input('item_discount');
        }else{
            $menuItem->item_discount = 0;
        }
        if ($request->input('item_discount_start')) {
            $menuItem->item_discount_start = $request->input('item_discount_start');
        }else{
            $menuItem->item_discount_start = NULL;
        }
        if ($request->input('item_discount_end')) {
            $menuItem->item_discount_end = $request->input('item_discount_end');
        }else{
            $menuItem->item_discount_end = NULL;
        }
        $menuItem->item_category = $request->input('item_category');
        $menuItem->item_is = $request->input('item_is');
        $menuItem->item_display_in = $request->input('item_display_in');
        $menuItem->item_for = json_encode($request->input('item_for'));
        $menuItem->show_at_home = $request->input('show_at_home');
        $menuItem->is_delicous = $request->input('is_delicous');
        $menuItem->is_you_may_like = ($request->input('is_you_may_like')?'Yes':'No');
        $menuItem->item_status = $request->input('item_status');
        $menuItem->is_non_discountAble = ($request->input('is_non_discountAble')?1:0);
        $menuItem->updated_by = $user_id;
        $menuItem->updated_at = new DateTime;
        $menuItem->save();

        self::insertUpdateAttributes($request, $menuItem);

        Session::flash ( 'success', "Menu Item Updated." );
        return Redirect::route('menuItem.index');
    }

    public function insertUpdateAttributes($request, $menuItem)
    {
        $user_id = Auth::user()->user_id;
        if ($menuItem->item_is == 'Attributes') {
            $attributes = $request->input('attribute');
            $item_attr_id = [];
            if (is_array($attributes) && !empty($attributes)) {
                foreach ($attributes as $attribute) {
                    if (!$itemAttributes = MenuItemAttributes::find($attribute['item_attr_id'])) {
                        $itemAttributes = new MenuItemAttributes();
                        $itemAttributes->updated_at = new DateTime;
                        $itemAttributes->menu_item_id = $menuItem->menu_item_id;
                    }
                    $itemAttributes->user_id = $user_id;
                    $itemAttributes->menu_attr_id = $attribute['menu_attr_id'];
                    $itemAttributes->attr_name = $attribute['attr_name'];
                    $itemAttributes->attr_price = $attribute['attr_price'];
                    $itemAttributes->attr_desc = $attribute['attr_desc'];
                    $itemAttributes->attr_status = $attribute['attr_status'];
                    $itemAttributes->created_at = new DateTime;
                    $itemAttributes->updated_at = new DateTime;
                    $itemAttributes->save();
                    $item_attr_id[] = $itemAttributes->item_attr_id;
                }
            }
            if ($item_attr_id) {
                MenuItemAttributes::whereNotIn('item_attr_id', $item_attr_id)->where('menu_item_id', $menuItem->menu_item_id)->delete();
            }            
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menuItem = MenuItems::find($id);
        $menuItem->delete();

        Session::flash ( 'success', "Menu Item Deleted." );
        return Redirect::route('menuItem.index');
    }

    public function getItemAttribute(Request $request)
    {
        $count = $request->input('count');
        $attribute = new \App\MenuItemAttributes();
        echo view('Admin.MenuItems.ItemAttribute', compact('attribute','count'));
    }
    public function updateOrder(Request $request){
        $orders = $request->input('order');
        if (!empty($orders) && is_array($orders)) {
            $index = 1;
            foreach ($orders as $order) {
                $menuItem = MenuItems::find($order);
                $menuItem->menu_order = $index;
                $menuItem->save();
                $index++;
            }
        }
    }
    public function sortItemCat()
    {
        $store_id = Auth::user()->store_id;
        $menuItemcategories = MenuItemsCategory::where('store_id', $store_id)
                        ->orderBy('menu_order', 'ASC')
                        ->select('item_cat_id','cat_name','menu_order')
                        ->get()->toArray();
        foreach ($menuItemcategories as &$menuItemcategory) {
            $menuItemcategory['menuItems'] = MenuItems::where('store_id', $store_id)
                        ->where('item_category', $menuItemcategory['item_cat_id'])
                        ->select('menu_item_id','item_name','menu_order')
                        ->orderBy('menu_order', 'ASC')
                        ->get()
                        ->toArray();
        }
        $view = 'Admin.MenuItems.sortItemCat';
        return view('Admin', compact('view','menuItemcategories'));
    }
}

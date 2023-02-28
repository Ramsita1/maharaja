<?php

namespace App\Http\Controllers\Admin\MenuItems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\Stores;
use App\MenuItemsCategory;

class MenuItemsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store_id = Auth::user()->store_id;
        $menuItemcategories = MenuItemsCategory::where('store_id', $store_id)->orderBy('menu_order', 'ASC')->paginate(pagination());

        $view = 'Admin.MenuItemsCategory.Index';
        return view('Admin', compact('view','menuItemcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menuItemCategory = new MenuItemsCategory();
        $view = 'Admin.MenuItemsCategory.CreateEdit';
        return view('Admin', compact('view','menuItemCategory'));
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
            'cat_name' => 'required|string|max:255',     
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
        $cat_name = $request->input('cat_name');
        $postCount = MenuItemsCategory::where('cat_name', $cat_name)->get()->count();
        if ($postCount > 0) {
            $cat_slug = $cat_name.' '.$postCount;
        }else{
            $cat_slug = $cat_name;
        }
        $cat_slug = str_slug($cat_slug, '-');
        $menuItemCategory = new MenuItemsCategory();
        $menuItemCategory->store_id = $user->store_id;
        $menuItemCategory->cat_name = $cat_name;
        $menuItemCategory->cat_slug = $cat_slug;
        $menuItemCategory->cat_description = $request->input('cat_description');
        $menuItemCategory->cat_image = $request->input('cat_image');
        $menuItemCategory->cat_status = $request->input('cat_status');
        $menuItemCategory->created_by = $user->user_id;
        $menuItemCategory->updated_by = $user->user_id;
        $menuItemCategory->created_at = new DateTime;
        $menuItemCategory->updated_at = new DateTime;
        $menuItemCategory->save();

        Session::flash ( 'success', "Menu Item Category Created." );
        return Redirect::route('menuItemCategory.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menuItemCategory = MenuItemsCategory::find($id);
        $view = 'Admin.MenuItemsCategory.CreateEdit';
        return view('Admin', compact('view','menuItemCategory'));
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

        $menuItemCategory = MenuItemsCategory::find($id);
        $menuItemCategory->cat_name = $request->input('cat_name');
        $menuItemCategory->cat_description = $request->input('cat_description');
        $menuItemCategory->cat_image = $request->input('cat_image');
        $menuItemCategory->updated_by = $user_id;
        $menuItemCategory->updated_at = new DateTime;
        $menuItemCategory->save();

        Session::flash ( 'success', "Menu Item Category Updated." );
        return Redirect::route('menuItemCategory.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menuItemCategory = MenuItemsCategory::find($id);
        $menuItemCategory->delete();

        Session::flash ( 'success', "Menu Item Category Deleted." );
        return Redirect::route('menuItemCategory.index');
    }
    public function updateOrder(Request $request){
        $orders = $request->input('order');
        if (!empty($orders) && is_array($orders)) {
            $index = 1;
            foreach ($orders as $order) {
                $menuItemCategory = MenuItemsCategory::find($order);
                $menuItemCategory->menu_order = $index;
                $menuItemCategory->save();
                $index++;
            }
        }
    }
}

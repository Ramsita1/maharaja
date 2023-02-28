<?php

namespace App\Http\Controllers\Admin\MenuItems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth, Validator;
use App\MenuItemBanners;

class MenuItemBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store_id = Auth::user()->store_id;
        $menuItemBanners = MenuItemBanners::where('store_id', $store_id)->paginate(pagination());

        $view = 'Admin.MenuItemBanners.Index';
        return view('Admin', compact('view','menuItemBanners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menuItemBanner = new MenuItemBanners();
        $view = 'Admin.MenuItemBanners.CreateEdit';
        return view('Admin', compact('view','menuItemBanner'));
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
            'banner_name' => 'required|string|max:255',
            'banner_image' => 'required|',   
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

        $menuItemBanner = new MenuItemBanners();
        $menuItemBanner->store_id = $user->store_id;
        $menuItemBanner->banner_name = $request->input('banner_name');
        $menuItemBanner->banner_image = $request->input('banner_image');
        $menuItemBanner->created_at = new DateTime;
        $menuItemBanner->updated_at = new DateTime;
        $menuItemBanner->save();

        Session::flash ( 'success', "Menu Banner Created." );
        return Redirect::route('menuItemBanner.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menuItemBanner = MenuItemBanners::find($id);
        $view = 'Admin.menuItemBanners.CreateEdit';
        return view('Admin', compact('view','menuItemBanner'));
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

        $menuItemBanner = MenuItemBanners::find($id);
        $menuItemBanner->banner_name = $request->input('banner_name');
        $menuItemBanner->banner_image = $request->input('banner_image');
        $menuItemBanner->updated_at = new DateTime;
        $menuItemBanner->save();

        Session::flash ( 'success', "Menu Banner Updated." );
        return Redirect::route('menuItemBanner.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menuItemBanner = MenuItemBanners::find($id);   
        $menuItemBanner->delete();

        Session::flash ( 'success', "Menu Banner Deleted." );
        return Redirect::route('menuItemBanner.index');
    }
}

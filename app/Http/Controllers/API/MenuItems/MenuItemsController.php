<?php

namespace App\Http\Controllers\API\MenuItems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\User;
use App\Stores;
use App\MenuItems;
use App\MenuItemsCategory;
use App\MenuItemAttributes;
use App\Printer;

class MenuItemsController extends Controller
{
       
    public function storeItemCat(Request $request)
    {
        $siteUrl = siteUrl()."/"."public"."/";

        // echo "<pre>";
        // print_r(getApiCurrentUser());
        // die;

        $currentUserId = getApiCurrentUser()->user_id;
        // $store = Stores::where('user_id', $currentUserId)->get()->first();
        $store = Stores::where('store_id', getApiCurrentUser()->store_id)->get()->first();
        $store_id = $store->store_id;
        
        $menuItem = [];
        
        $menuItemcategories = MenuItemsCategory::where('store_id', $store_id)
                        ->orderBy('menu_order', 'ASC')
                        ->select('item_cat_id','cat_name','menu_order')
                        ->get()->toArray();

        

        foreach ($menuItemcategories as $menuItemcategory) {
            $menuItemcategory['menuItems'] = MenuItems::where('store_id', $store_id)
                        ->where('item_category', $menuItemcategory['item_cat_id'])
                        ->select('menu_item_id','item_name','item_description','item_image','item_price','item_sale_price','item_discount','item_status','menu_order')
                        ->orderBy('menu_order', 'ASC')
                        ->get()
                        ->toArray();
            if($menuItemcategory['menuItems'] != [])
            {
                $menuItemcategory['menuItems'] = array_map(function($data)use($siteUrl){
                    if($data['item_image'] != "")
                    {
                        $data['item_image'] = $siteUrl.$data['item_image'];
                    }
                               return $data;
                }, $menuItemcategory['menuItems']);
            }
            $menuItem[] = $menuItemcategory;            
        }
        return Response()->json(['status'=>'success', 'message' => 'Menu items and sub items', 'response' => compact('menuItem') ],200);


    }

    public function updateRules()
	{
		return [
		    'item_status' => 'required|in:Active,Inactive',
		    'menu_item_id' => 'required|numeric',
		];
	}

    public function updateMenuItemStatus(Request $request)
    {
        $validator = Validator::make($request->all(), self::updateRules());

		if($validator->fails()){
		    return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],401);
        }
        
        $menuItem = MenuItems::find($request->menu_item_id);
        $menuItem->item_status = $request->input('item_status');
        $menuItem->save();

        return Response()->json(['status'=>'success', 'message' => 'Menu items update successfully'],200);
        //return Response()->json(['status'=>'success', 'message' => 'Menu items update successfully', 'response' => compact('menuItem') ],200);
    }

    public function getPrinters(Request $request)
    {
        $store = Stores::where('store_id', getApiCurrentUser()->store_id)->get()->first();
        $store_id = $store->store_id;

        $printers = Printer::where('store_id', $store_id)
                        ->get()->toArray();

        return Response()->json(['status'=>'success', 'message' => 'Printers list', 'response' => compact('printers') ],200);

    }

    public function addPrinterRules()
	{
		return [
			'printer_name' => 'required',
			'printer_ip_address' => 'required'
		];
	}  
    public function addPrinter(Request $request)
    {
        $validator = Validator::make($request->all(), self::addPrinterRules());
		if($validator->fails()){
		    return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],401);
		}
        $store = Stores::where('store_id', getApiCurrentUser()->store_id)->get()->first();
        $store_id = $store->store_id;
        $printer= Printer::create([
            'printer_name'=>$request->input('printer_name'),
             'printer_ip_address'=>$request->input('printer_ip_address'),
             'store_id'=>$store_id
    
        ]);
        return Response()->json(['status'=>'success', 'message' => 'Printer Added Successfully', 'response' => compact('printer') ],200);
    }
    public function updatePrinter(Request $request)
    {
        $validator = Validator::make($request->all(), self::addPrinterRules());
		if($validator->fails()){
		    return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],401);
		}
        $printer = Printer::find($request->id);
        $printer->printer_name = $request->input('printer_name');
        $printer->printer_ip_address = $request->input('printer_ip_address');
        $printer->save();
      
        return Response()->json(['status'=>'success', 'message' => 'Printer Updated Successfully', 'response' => compact('printer') ],200);
    }
    public function deletePrinter(Request $request)
    {
        
        $printer = Printer::find($request->id);
        
         $p=$printer->delete();
      
        return Response()->json(['status'=>'success', 'message' => 'Printer Deleted Successfully', 'response' => compact('p') ],200);
    }
    
}

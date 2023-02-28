<?php

namespace App\Http\Controllers\API\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\Stores;
use App\MenuItems;
use App\MenuItemType;
use App\MenuItemsCategory;
use App\StoresHolidays;
use App\StoreOnlineOrderTimings;
use App\MenuAttributes;
use App\MenuItemAttributes;
use App\StoreDeliveryLocationPrice;
use App\StorePickupLocations;
use App\MenuItemBanners;
use App\User;
use App\Vouchers;
use App\Deals;
use App\ProductOrder;
use App\TempCartTable;
use App\DeviceToken;
use App\PhoneOtpVerification;

class EstoreController extends Controller
{

    // public function searchStore(Request $request)
    // {
    //     $keyword = $request->input('term');
    //     $rows = [];
    //     if (empty($keyword)) {
    //         $rows[] = [
    //             'value' => '',
    //             'id' => '',
    //             'label' => ''
    //         ];
    //     }
    //     $storeIDS = StorePickupLocations::where(function($query) use($keyword){
    //                     if ($keyword) {
    //                         $query->orwhere('city', 'LIKE', '%'.$keyword.'%');
    //                         $query->orwhere('suburb', 'LIKE', '%'.$keyword.'%');
    //                         $query->orwhere('postal_code', 'LIKE', '%'.$keyword.'%');
    //                     }
    //                 })->select('store_id')->get()->pluck('store_id')->toArray();
    //     $stores = Stores::select('store_id','store_title','store_address','store_suburb','store_postalCode')
    //                 ->where(function($query) use($keyword, $storeIDS){
    //                     if ($keyword) {
    //                         $query->orwhere('store_title', 'LIKE', '%'.$keyword.'%');
    //                         $query->orwhere('store_address', 'LIKE', '%'.$keyword.'%');
    //                         $query->orwhere('store_suburb', 'LIKE', '%'.$keyword.'%');
    //                         $query->orwhere('store_postalCode', 'LIKE', '%'.$keyword.'%');
    //                     }
    //                     if ($storeIDS) {
    //                         $query->orwhereIn('store_id', $storeIDS);
    //                     }
    //                 })->paginate(pagination());
    //     foreach ($stores as $store) {
    //         $rows[] = [
    //             'label' => $store->store_title.' '.$store->store_postalCode.' '.$store->store_suburb.' '.$store->store_address,
    //             'value' => $store->store_title.' '.$store->store_postalCode.' '.$store->store_suburb.' '.$store->store_address,
    //             'id' => str_replace(' ','-',$store->store_title.'-'.$store->store_id)
    //         ];
    //     }
    //     return json_encode($rows);
    // }

    public function pickupStore($pickup_when = 'Now', $storeSlug = null)
    {
        if (empty($storeSlug)) {
            return Response()->json(['status'=>'success', 'message' => 'slug not found' ],200);
            // return Response()->json(['status'=>'success', 'message' => 'slug not found'], 'response' => [] ], 401);
        }
        $storeOriginalSlug = $storeSlug;
        $title = str_replace('-',' ',$storeSlug).' | Maharaja Hotel';
        $storeSlug = explode('-', $storeSlug);
        $store_id = end($storeSlug);
        $store = Stores::select('store_id','store_title','store_address','store_suburb','store_postalCode','store_city')
                    ->where('store_id', $store_id)->get()->first();
        if (!$store) {
            return Response()->json(['status'=>'success', 'message' => 'Store not found on your selected location, check your postal code and suburb.' ], 401);
        }
        
        $storeTimings = StoreOnlineOrderTimings::where('store_id', $store_id)->where('type' ,'StoreOnlineOrderTimingsPickup')->get()->first();
        if($storeTimings) {
            $weekdays = maybe_decode($storeTimings->weekdays);
        } else {
            $weekdays = [];
        }
        
        $holidays = StoresHolidays::where('store_id', $store_id)->where('status', 1)->get();
        $datesArray = [];
        foreach ($holidays as $holiday) {
            $datesArray[] = $holiday->date;
        }
        if ($pickup_when == 'Now') {
            $currentDay = date('D');
            $currentSlotExit = false;
            if (isset($weekdays[$currentDay]['status']) && $weekdays[$currentDay]['status'] == 1) {
               $slotes = $weekdays[$currentDay];
               foreach (itemFor() as $itemFor) {
                  if (isset($slotes[$itemFor]['open_time']) && !empty($slotes[$itemFor]['open_time']) && isset($slotes[$itemFor]['close_time']) && !empty($slotes[$itemFor]['close_time'])) {
                     $open_time = date('His', strtotime($slotes[$itemFor]['open_time']));
                     $close_time = date('His', strtotime($slotes[$itemFor]['close_time']));
                     if ($open_time < date('His') && $close_time > date('His')) {
                        $currentSlotExit = date('His');
                     }
                  }
               }
            }
            if ($currentSlotExit) {
                $url = url('estore/items').'?store='.$storeOriginalSlug.'&pickup_when='.$pickup_when.'&order_type=Pickup&order_date='.date('Ymd').'&order_time='.$currentSlotExit.'&submit=Next';
                //return redirect($url);
                return Response()->json(['status'=>'success', 'message' => 'later', 'response' => compact('url') ],200);
            }            
        }
        return Response()->json(['status'=>'success', 'message' => 'Order status updated successfully', 'response' => compact('store','pickup_when','weekdays','datesArray','currentSlotExit') ],200);
        //return view('OrderFront', compact('view','title','store','pickup_when','weekdays','datesArray'));
    }

    public function getSelectedTimes(Request $request)
    {
        $currentDay = $request->input('selectedDay');
        $store_id = $request->input('store_id');
        $selectedDate = $request->input('selectedDate');
        $orderType = $request->input('orderType');
        $anotherTimings = 'StoreOnlineOrderTimingsPickup';
        $selectedTime=[];
        if ($orderType == 'StoreOnlineOrderTimingsPickup') {
            $anotherTimings = 'StoreOnlineOrderTimingsDelivery';
        }
        $storeTimings = StoreOnlineOrderTimings::where('store_id', $store_id)->where('type' ,$orderType)->get()->first();
        if($storeTimings) {
            $weekdays = maybe_decode($storeTimings->weekdays);
        } else {
            $weekdays = [];
        }
        $holiday = StoresHolidays::where('store_id', $store_id)->where('date', $selectedDate)->where('status', 1)->get()->first();
        if ($holiday) {
            $storeCloseStartTime = date('His', strtotime($holiday->close_start_time));
            $storeCloseEndTime = date('His', strtotime($holiday->close_end_time));
        } else {
            $storeCloseStartTime = '';
            $storeCloseEndTime = '';
        }
        $html = '';
        if (isset($weekdays[$currentDay]['status']) && $weekdays[$currentDay]['status'] == 1) {
           $slotes = $weekdays[$currentDay];
           $cutOfTime = (isset($weekdays['cut_of_time'])?$weekdays['cut_of_time']:'15');
           foreach (itemFor() as $itemFor) {
              if (isset($slotes[$itemFor]['open_time']) && !empty($slotes[$itemFor]['open_time']) && isset($slotes[$itemFor]['close_time']) && !empty($slotes[$itemFor]['close_time'])) {
                 $open_time = date('His', strtotime($slotes[$itemFor]['open_time']));
                 $close_time = date('His', strtotime($slotes[$itemFor]['close_time'])).'-';
                 $sloteMinute = $slotes['interval'];
                 while ($open_time < $close_time) {
                    if ($storeCloseStartTime && $storeCloseEndTime) {
                        if ($storeCloseStartTime > $open_time || $storeCloseEndTime < $open_time) {
                            if ($selectedDate == date('Ymd')) {
                                if ($open_time > date('His', strtotime("+".$cutOfTime." minutes"))) {
                                   // $html .= '<option value="'.$open_time.'">'.date('h:i A', strtotime($open_time)).'</option>';
                                   $selectedTime[]=(object)['time' => $open_time, 'time1' => date('h:i A', strtotime($open_time))];
                                } 
                            }else{
                                //$html .= '<option value="'.$open_time.'">'.date('h:i A', strtotime($open_time)).'</option>';
                                $selectedTime[]=(object)['time' => $open_time, 'time1' => date('h:i A', strtotime($open_time))];
                            } 
                        }                         
                    } else {
                        if ($selectedDate == date('Ymd')) {
                            if ($open_time > date('His', strtotime("+".$cutOfTime." minutes"))) {
                                //$html .= '<option value="'.$open_time.'">'.date('h:i A', strtotime($open_time)).'</option>';
                                $selectedTime[]=(object)['time' => $open_time, 'time1' => date('h:i A', strtotime($open_time))];
                            } 
                        }else{
                            //$html .= '<option value="'.$open_time.'">'.date('h:i A', strtotime($open_time)).'</option>';
                            $selectedTime[]=(object)['time' => $open_time, 'time1' => date('h:i A', strtotime($open_time))];
                        }  
                    }
                                                          
                    $open_time = date('His', strtotime($open_time)+($sloteMinute*60));
                 }
              }
           }
        }
        if (!empty($html)) {
            return Response()->json(['status'=>'success',  'response' => compact('selectedTime') ],200);
            //echo '<option value="">Select Time</option>'.$html;
            //die;
        } else {
            $storeTimings = StoreOnlineOrderTimings::where('store_id', $store_id)->where('type' ,$anotherTimings)->get()->first();
            if($storeTimings) {
                $weekdays = maybe_decode($storeTimings->weekdays);
            } else {
                $weekdays = [];
            }  
            $pickup_when = $request->input('pickup_when');
            $currentSlotExit = false;
            if (isset($weekdays[$currentDay]['status']) && $weekdays[$currentDay]['status'] == 1) {
               $slotes = $weekdays[$currentDay];
               $cutOfTime = (isset($weekdays['cut_of_time'])?$weekdays['cut_of_time']:'15');
               foreach (itemFor() as $itemFor) {
                  if (isset($slotes[$itemFor]['open_time']) && !empty($slotes[$itemFor]['open_time']) && isset($slotes[$itemFor]['close_time']) && !empty($slotes[$itemFor]['close_time'])) {
                     $open_time = date('His', strtotime($slotes[$itemFor]['open_time']));
                     $close_time = date('His', strtotime($slotes[$itemFor]['close_time']));
                     if ($open_time < date('His', strtotime("+".$cutOfTime." minutes")) && $close_time > date('His', strtotime("+".$cutOfTime." minutes"))) {
                        $currentSlotExit = true;
                     }
                  }
               }
            } 
            // if ($currentSlotExit == true) {
            //     if ($orderType == 'StoreOnlineOrderTimingsPickup') {
            //         echo 'DeliveryAvailable';
            //         die;
            //     } else if ($orderType == 'StoreOnlineOrderTimingsDelivery') {
            //         echo 'PickupAvailable';
            //         die;
            //     }
            // }
        }
        //echo '<option value="">Select Time</option>';
        
        return Response()->json(['status'=>'success',  'response' => compact('selectedTime') ],200);
        die;
    }

    public function deliveryStore(Request $request)
    {
        //$view = 'Estore.DeliveryStoreTiming';
        $pickup_when = $request->input('pickup_when');
        $unit_number = $request->input('unit_number');
        $street_number = $request->input('street_number');
        $street = $request->input('street');
        $suburb = $request->input('suburb');
        $city = $request->input('city');
        $pincode = $request->input('pincode');
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        $store_ids = StoreDeliveryLocationPrice::where(function($query) use($pincode, $city, $suburb){
                        if ($suburb) {
                            $query->orwhere('suburb', 'LIKE', '%'.$suburb.'%');
                            $query->orwhere('city', 'LIKE', '%'.$suburb.'%');
                        }
                        if ($city) {
                            $query->orwhere('suburb', 'LIKE', '%'.$city.'%');
                            $query->orwhere('city', 'LIKE', '%'.$city.'%');
                        }
                        if ($pincode) {
                            $query->where('postal_code', 'LIKE', '%'.$pincode.'%');
                        }
                    })->select('store_id')->get();

        $stores = Stores::select('store_id','store_title','store_address','store_suburb','store_postalCode')
                    ->where(function($query) use($pincode, $suburb, $store_ids){
                        /*if ($suburb) {
                            $query->orwhere('store_suburb', 'LIKE', '%'.$suburb.'%');
                        }
                        if ($pincode) {
                            $query->orwhere('store_postalCode', 'LIKE', '%'.$pincode.'%');
                        }*/
                        if ($store_ids) {
                            $query->whereIn('store_id', $store_ids);
                        }
                    })->get();

        if (!count($stores)) {
            return Response()->json(['status'=>'success', 'message' => 'Stores not found on your selected location, check your postal code and suburb.' ],401);
        }
        if (count($stores) == 1 && $pickup_when == 'Now') {
            $store = $stores->first();
            $storeTimings = StoreOnlineOrderTimings::where('store_id', $store->store_id)->where('type' ,'StoreOnlineOrderTimingsDelivery')->get()->first();
            if($storeTimings) {
                $weekdays = maybe_decode($storeTimings->weekdays);
            } else {
                $weekdays = [];
            }
            
            $holidays = StoresHolidays::where('store_id', $store->store_id)->where('status', 1)->get();
            $datesArray = [];
            foreach ($holidays as $holiday) {
                $datesArray[] = $holiday->date;
            }
            $currentDay = date('D');
            $currentSlotExit = false;
            if (isset($weekdays[$currentDay]['status']) && $weekdays[$currentDay]['status'] == 1) {
               $slotes = $weekdays[$currentDay];
               foreach (itemFor() as $itemFor) {
                  if (isset($slotes[$itemFor]['open_time']) && !empty($slotes[$itemFor]['open_time']) && isset($slotes[$itemFor]['close_time']) && !empty($slotes[$itemFor]['close_time'])) {
                     $open_time = date('His', strtotime($slotes[$itemFor]['open_time']));
                     $close_time = date('His', strtotime($slotes[$itemFor]['close_time']));
                     if ($open_time < date('His') && $close_time > date('His')) {
                        $currentSlotExit = date('His');
                     }
                  }
               }
            }
            if ($currentSlotExit) {
                $urlString = http_build_query($request->all());
                $storeOriginalSlug = str_replace(' ','-',$store->store_title.'-'.$store->store_id);
                $url = url('api/v1/estore/items').'?store='.$storeOriginalSlug.'&order_date='.date('Ymd').'&order_time='.$currentSlotExit.'&order_type=Delivery&'.$urlString;
                //return redirect($url);
                return Response()->json(['status'=>'success',  'response' => compact('url') ],200);
            } 
        }
        $title = $suburb.' - '.$pincode.' | Maharaja Hotel';
        //return view('OrderFront', compact('view','title','stores','pickup_when','unit_number','city','street_number','street','suburb','pincode','lat','lng'));
        return Response()->json(['status'=>'closed', 'message' =>'Sorry! this store is currently closed Pick a Date & Time for your future Order' ,'response' => compact('title','stores','pickup_when','unit_number','city','street_number','street','suburb','pincode','lat','lng') ],200);
    }

    public function getStoreDates(Request $request)
    {
        $store_id = $request->input('store_id');
        $orderType = $request->input('orderType');
        $storeTimings = StoreOnlineOrderTimings::where('store_id', $store_id)->where('type' ,$orderType)->get()->first();
        if($storeTimings) {
            $weekdays = maybe_decode($storeTimings->weekdays);
        } else {
            $weekdays = [];
        }        
        
        $pickup_when = $request->input('pickup_when');
        $currentSlotExit = false;
        if ($pickup_when == 'Now') {
            $currentDay = date('D');
            if (isset($weekdays[$currentDay]['status']) && $weekdays[$currentDay]['status'] == 1) {
               $slotes = $weekdays[$currentDay];
               foreach (itemFor() as $itemFor) {
                  if (isset($slotes[$itemFor]['open_time']) && !empty($slotes[$itemFor]['open_time']) && isset($slotes[$itemFor]['close_time']) && !empty($slotes[$itemFor]['close_time'])) {
                     $open_time = date('His', strtotime($slotes[$itemFor]['open_time']));
                     $close_time = date('His', strtotime($slotes[$itemFor]['close_time']));
                     if ($open_time < date('His') && $close_time > date('His')) {
                        $currentSlotExit = date('His');
                     }
                  }
               }
            }         
        }
        if ($currentSlotExit) {
            echo 'currentSlotExit';
            die;
        }
        
        $selectedAttr = 'selected';
        $selectedDate = '';
        $dateResult=[];
        foreach (getDateRange(20) as $date) {
           $day = date('D', strtotime($date));
           if (isset($weekdays[$day]['status']) && $weekdays[$day]['status'] == 1) {
              if (!$selectedDate) {
                 $selectedDate = $date;
              }
              //echo '<option value="'.$date.'" data-day="'.$day.'" '.$selectedAttr.'>'.date('D d M Y', strtotime($date)).'</option>';
              $dateResult[] = (object)['date' => $date, 'day' => $day,'dateFull'=>date('D d M Y', strtotime($date))];
              $selectedAttr = '';
           }                                             
        }
        return Response()->json(['status'=>'success',  'response' => compact('dateResult') ],200);
    }

    // public function storeItemsRules()
	// {
	// 	return [
	// 	    'store' => 'required',
	// 	    'pickup_when' => 'required',
	// 		'order_date' => 'required',
	// 		'order_time' => 'required',
	// 		'item_name' => 'required',
	// 		'item_display_in' => 'required',
	// 		'item_for' => 'required',
	// 		'is_delicous' => 'required',
	// 		'category_name' => 'required',
	// 		'order_type' => 'required',
	// 	];
	// }

    public function storeItems(Request $request)
    {
        // $validator = Validator::make($request->all(), self::storeItemsRules());
        // if($validator->fails()){
		//     return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],401);
		// }


        $storeSlug = $request->input('store');
        $pickup_when = $request->input('pickup_when');
        $orderDate = $request->input('order_date');
        $orderTime = $request->input('order_time');
        $item_name = $request->input('item_name');

        $item_display_in = $request->input('item_display_in');
        $item_for = $request->input('item_for');
        $is_delicous = $request->input('is_delicous');
        $category_name = $request->input('category_name');

        $title = str_replace('-',' ',$storeSlug).' | Maharaja Hotel';
        $storeSlug = explode('-', $storeSlug);
        $store_id = end($storeSlug);
        $store = Stores::where('store_id', $store_id)->get()->first();
       
        if (!$store) {
            return Response()->json(['status'=>'success', 'message' => 'Store not found on your selected location, check your postal code and suburb.', 'response' => [] ], 401);
        }
        $orderType = 'StoreOnlineOrderTimingsDelivery';
        if ($request->input('order_type') == 'Pickup') {
            $orderType = 'StoreOnlineOrderTimingsPickup';
        }
        $storeTimings = StoreOnlineOrderTimings::where('store_id', $store_id)->where('type' ,$orderType)->get()->first();
        if($storeTimings) {
            $weekdays = maybe_decode($storeTimings->weekdays);
        } else {
            $weekdays = [];
        }
        $day = date('D', strtotime($orderDate));
        $weekday = $weekdays[$day];
        $currentSlotExit = false;
        foreach (itemFor() as $itemFor) {
           if (isset($weekday[$itemFor]['open_time']) && !empty($weekday[$itemFor]['open_time']) && isset($weekday[$itemFor]['close_time']) && !empty($weekday[$itemFor]['close_time'])) {
              $open_time = date('His', strtotime($weekday[$itemFor]['open_time']));
              $close_time = date('His', strtotime($weekday[$itemFor]['close_time']));
              if ($open_time < $orderTime && $close_time > $orderTime) {
                 $currentSlotExit = $itemFor;   
              }
           }
        }
        $menuItems = MenuItems::where('menu_items.store_id', $store_id)
                    ->join('menu_items_category','menu_items_category.item_cat_id','menu_items.item_category')
                    ->select('menu_items.item_category','menu_items.item_name','menu_items.item_price','menu_items.item_sale_price','menu_items.menu_item_id','menu_items_category.cat_name')
                    ->where(function($query) use($item_display_in, $item_for, $is_delicous, $category_name, $item_name){
                        if ($item_display_in) {
                            $query->whereIn('item_display_in', $item_display_in);
                        }
                        if ($item_for) {
                            $item_for[] = 'all';
                            foreach ($item_for as $item_for_d) {
                                $qu = '"'.$item_for_d.'":{"status":"0"';
                                $query->whereRaw("item_for LIKE '%".$qu."%'");
                            }
                        }
                        if ($is_delicous) {
                            $query->whereIn('is_delicous', $is_delicous);
                        }
                        if ($category_name) {
                            $query->whereIn('menu_items_category.cat_slug', $category_name);
                        }
                        if ($item_name) {
                            $query->whereRaw("item_name LIKE '%".$item_name."%'");
                        }
                    })
                    ->where(function($query) use($currentSlotExit){
                        if ($currentSlotExit) {
                            $qu = '"'.$currentSlotExit.'":{"status":"1"';
                            $query->whereRaw("item_for LIKE '%".$qu."%'");
                            //$query->whereRaw("json_extract(item_for, '$.".$currentSlotExit.".status') = 1");
                        }
                    })
                    ->get()->toArray();
        $requestPerameters = $request->all();
        if (isset($requestPerameters['item_display_in'])) {
            unset($requestPerameters['item_display_in']);
        }
        if (isset($requestPerameters['item_for'])) {
            unset($requestPerameters['item_for']);
        }
        if (isset($requestPerameters['is_delicous'])) {
            unset($requestPerameters['is_delicous']);
        }
        if (isset($requestPerameters['category_name'])) {
            unset($requestPerameters['category_name']);
        }
        if (isset($requestPerameters['item_name'])) {
            unset($requestPerameters['item_name']);
        }
        $requestPerameters['slot'] = $currentSlotExit;
        Session::put('delivery_pickup_address', $requestPerameters); 
        Session::save();  
        $item_display_in = (is_array($item_display_in)?$item_display_in:[]);
        $item_for = (is_array($item_for)?$item_for:[]);
        $is_delicous = (is_array($is_delicous)?$is_delicous:[]);
        $category_name = (is_array($category_name)?$category_name:[]);
        $itemPage = true;
        $banners = MenuItemBanners::where('store_id', $store_id)->get();
        $openPage = 'itemPage';

        return Response()->json(['status'=>'success', 'message' => 'Menu items and sub items', 'response' => compact('view','title','store','pickup_when','orderDate','orderTime','menuItems','item_display_in','item_for','is_delicous','category_name','requestPerameters','currentSlotExit','itemPage','banners','openPage') ],200);

        //return view('OrderFront', compact('view','title','store','pickup_when','orderDate','orderTime','menuItems','item_display_in','item_for','is_delicous','category_name','requestPerameters','currentSlotExit','itemPage','banners','openPage'));
    }

    public function getMenuItemAttributes(Request $request)
    {
        $store_id = $request->input('store_id');
        $menu_item_id = $request->input('menu_item_id');
        $menuItem = \App\MenuItems::where('menu_item_id', $menu_item_id)->where('store_id', $store_id)->get()->first();
        //$menuItem->menuAttributes = 
        $menuAttributes = MenuAttributes::where('store_id', $store_id)
                    //  ->join('menu_items_category','menu_items_category.item_cat_id','menu_items.item_category')
                    ->whereIn('menu_attr_id', MenuItemAttributes::where('menu_item_id', $menu_item_id)->select('menu_attr_id')->get())
                    ->where('attr_status', 'Active')
                    ->orderBy('attr_main_choice', 'DESC')
                    ->get();
       $menuItem->menuAttributes = $menuAttributes;

        foreach ($menuAttributes as $menuAttribute) {
            $attributes = MenuItemAttributes::where('menu_item_id', $menu_item_id)->where('menu_attr_id', $menuAttribute->menu_attr_id)->orderBy('attr_price', 'ASC')->get();   
            $attributers[] = $attributes;
            
        }           
        // return Response()->json(['status'=>'success', 'message' => 'Specify your Dish', 'response' => compact('attributers') ],200);  
    
        // $menuAttributes->att= $attributes;
        // $item_name = menuItem;
        return Response()->json(['status'=>'success', 'message' => 'Specify your Dish', 'response' => compact('menuItem','attributers') ],200);            
        
    }

    public function addToCart(Request $request)
    {         
        $currentUser = getApiCurrentUser();
        $user_id = $currentUser->user_id ? ($currentUser->user_id) : null;
        
        $menu_item_id = $request->input('menu_item_id');
        $quantity = $request->input('quantity');
        $item_page = $request->input('item_page');
        $store_id = $request->input('store_id');
        // Session::put('cartData', []); 
        // Session::save();
        $menuItem = MenuItems::where('store_id', $store_id)->where('menu_item_id', $menu_item_id)->get()->first();
        $temp_cart =TempCartTable::where('user_id', $user_id)->where('store_id', $store_id)->get()->first();
        
        //echo '<pre>'; echo($cartData); echo '</pre>';
        // die;
        if ($temp_cart) {
            $cartData=json_decode($temp_cart->attributes,true);
        }
        else{
            $cartData = [];
        }
        if (!$menuItem) {
            // $response = [
            //     'count' => count($cartData),
            //     'status' => 'warning',
            //     'message' => 'Something went wrong please try after sometime'
            // ];
            // echo json_encode($response);
            // die;
            return Response()->json(['status'=>'warning', 'message' => 'Something went wrong please try after sometime', 'count' => count($cartData) ], 401);
        } 
        if ($request->input('type') == 'attribute') {   
            $addAttribute =  self::addToCartAttribute($request, $cartData, $menuItem);
            return Response()->json(['status'=>'success', 'response' => $addAttribute ], 200);
        }
        if (isset($cartData[$menu_item_id]) && !empty($cartData[$menu_item_id])) {
            if ($item_page == 'cartPage' || $item_page == 'checkoutPage') {
                $cartData[$menu_item_id]['item_quantity'] = $quantity;
            } else {
                $cartData[$menu_item_id]['item_quantity'] = $cartData[$menu_item_id]['item_quantity'] + $quantity;
            }
            
            $cartData[$menu_item_id]['item_total_price'] = $cartData[$menu_item_id]['item_quantity'] * $cartData[$menu_item_id]['item_price'];           

        } else {    
            $cartData[$menu_item_id] = [
                'menu_item_id' => $menu_item_id,
                'item_price' => itemShowPrice($menuItem),
                'item_quantity' => $quantity,
                'item_total_price' => itemShowPrice($menuItem) * $quantity,
                'item_name' => $menuItem->item_name,
                'store_id' => $store_id
            ];
        }
        if (isset($cartData[$menu_item_id]) && $cartData[$menu_item_id]['item_quantity'] == '0') {
            unset($cartData[$menu_item_id]);
        }
        Session::put('cartData', $cartData); 
        Session::save();  
        self::removeVoucherAuto();
        $cartData = Session::get ( 'cartData' );
        if (empty($cartData)) {
            Session::put('delivery_pickup_address', []);
            Session::save();
            // $response = [
            //     'count' => count($cartData),
            //     'status' => 'warning',
            //     'message' => 'Cart empty now. Please add item to cart.',
            //     'cartHtml' => ''
            // ];
            return Response()->json(['status'=>'warning', 'message' => 'Cart empty now. Please add item to cart.', 'count' => count($cartData), 'cartHtml' => '' ], 401);
        } else {
            if ($item_page == 'checkoutPage') {
                ob_start();
                    echo view('Estore.StoreCheckout');
                $cartHtml = ob_get_clean();         
            } else {
                $cartHtml = getCartHtml(true);
            }

            if ($item_page == 'cartPage' || $item_page == 'checkoutPage') {
                // $response = [
                //     'count' => count($cartData),
                //     'status' => 'success',
                //     'message' => 'Cart item updated successfully.',
                //     'cartHtml' => $cartHtml
                // ];
                return Response()->json(['status'=>'success', 'message' => 'Cart item updated successfully', 'count' => count($cartData), 'cartHtml' => $cartHtml ], 200);
            } else {
                // $response = [
                //     'count' => count($cartData),
                //     'status' => 'success',
                //     'message' => 'Item add to cart successfully.',
                //     'cartHtml' => $cartHtml
                // ];
                return Response()->json(['status'=>'success', 'message' => 'Item add to cart successfully.', 'count' => count($cartData), 'cartHtml' => $cartHtml ], 200);
            }
        }
        // echo json_encode($response);
        // die;
    }

    public function addToCartAttribute($request, $cartData, $menuItem)
    {  
        $currentUser = getApiCurrentUser();
        $user_id = $currentUser->user_id ? ($currentUser->user_id) : null;
        //echo $currentUser ; die;
        $menu_item_id = $request->input('menu_item_id');
        $quantity = $request->input('quantity');
        $item_page = $request->input('item_page');
        $store_id = $request->input('store_id');
        $item_attrs = $request->input('item_attr_id');
        
        
        $item_attr_id = [];
        if (is_array($item_attrs) && !empty($item_attrs)) {
            foreach ($item_attrs as $item_attr) {
                $item_attr_id = array_merge($item_attr_id, $item_attr);
            }
        }
        //echo '<pre>'; print_r($item_attrs); echo '</pre>';
        //echo '<pre>'; print_r($item_attr_id); echo '</pre>';die;
        $attr_type = $request->input('attr_type');
        // echo "<pre>";
        // print_r($attr_type);
        // die();
        if ($item_page == 'cartPage' || $item_page == 'checkoutPage') {
            $item_attr_id = explode('-', $request->input('item_attributeids'));
        }
        arsort($item_attr_id);
        
        if (is_array($item_attr_id) && !empty($item_attr_id) || $menu_item_id) {
            $mainAttr = MenuItemAttributes::join('menu_attributes','menu_attributes.menu_attr_id','menu_item_attributes.menu_attr_id')
                ->whereIn('item_attr_id', $item_attr_id)
                ->where('menu_attributes.attr_main_choice', 1)
                ->select('menu_item_attributes.attr_name as item_attr_name','menu_item_attributes.attr_price', 'menu_item_attributes.item_attr_id','menu_attributes.attr_name as menu_attr_name','menu_attributes.attr_type')
                ->orderBy('attr_price', 'ASC')->get()->first();
            $stringAttrID = $menu_item_id.'-'.implode('-',$item_attr_id);
            //echo '<pre>'; print_r($cartData); echo '</pre>';
            //echo '<pre>'; print_r($stringAttrID); echo '</pre>';die;

            // echo "haha".$stringAttrID;die();

            if (isset($cartData[$stringAttrID])) {
                if ($item_page == 'cartPage' || $item_page == 'checkoutPage') {
                    $cartData[$stringAttrID]['item_quantity'] = $quantity;
                } else {
                    $cartData[$stringAttrID]['item_quantity'] = $cartData[$stringAttrID]['item_quantity'] + $quantity;
                }
                $cartData[$stringAttrID]['item_total_price'] = $cartData[$stringAttrID]['item_quantity'] * $cartData[$stringAttrID]['item_price'];

                if ($mainAttr) {
                    $cartData[$stringAttrID]['attributes'][$mainAttr->item_attr_id]['quantity'] = $cartData[$stringAttrID]['item_quantity'];
                }                

                $attributes = MenuItemAttributes::join('menu_attributes','menu_attributes.menu_attr_id','menu_item_attributes.menu_attr_id')
                    ->whereIn('item_attr_id', $item_attr_id)
                    ->where('menu_attributes.attr_main_choice', 0)
                    ->select('menu_item_attributes.attr_name as item_attr_name','menu_item_attributes.attr_price', 'menu_item_attributes.item_attr_id','menu_attributes.attr_name as menu_attr_name','menu_attributes.attr_type')
                    ->get();

                   
                foreach ($attributes as $attribute) {
                    if (isset($cartData[$stringAttrID]['attributes'][$attribute->item_attr_id])) {
                        $cartData[$stringAttrID]['attributes'][$attribute->item_attr_id]['quantity'] = $cartData[$stringAttrID]['item_quantity'];

                        $cartData[$stringAttrID]['attributes'][$attribute->item_attr_id]['attr_total_price'] = ($cartData[$stringAttrID]['item_quantity'] * $cartData[$stringAttrID]['attributes'][$attribute->item_attr_id]['attr_price']);

                    } else {
                        $cartData[$stringAttrID]['attributes'][$attribute->item_attr_id] = [
                            'item_attr_id' => $attribute->item_attr_id,
                            'attr_name' => $attribute->menu_attr_name.'-'.$attribute->item_attr_name,
                            'attr_type' => $attribute->attr_type,
                            'attr_price' => $attribute->attr_price,
                            'quantity' => $quantity,
                            'attr_total_price' => ($quantity * $attribute->attr_price)
                        ];
                    }                    
                }
            } else {   
                $mainAttrAvail = true;             
                if (!$mainAttr) {
                    $mainAttr = new MenuItemAttributes();
                    $mainAttrAvail = false;
                }
                $attr_price_withMain = (itemShowPrice($menuItem)+$mainAttr->attr_price);
                $cartData[$stringAttrID] = [
                    'menu_item_id' => $menu_item_id,
                    'item_price' => $attr_price_withMain,
                    'item_quantity' => $quantity,
                    'item_total_price' => $attr_price_withMain * $quantity,
                    'item_name' => $menuItem->item_name,
                    'store_id' => $store_id,
                    'item_attr_id' => $mainAttr->item_attr_id
                ];
                if ($mainAttr && $mainAttrAvail == true) {
                    $cartData[$stringAttrID]['attributes'][$mainAttr->item_attr_id] = [
                        'item_attr_id' => $mainAttr->item_attr_id,
                        'attr_name' => $mainAttr->menu_attr_name.'-'.$mainAttr->item_attr_name,
                        'attr_type' => $mainAttr->attr_type,
                        'attr_price' => 0,
                        'quantity' => $quantity,
                        'attr_total_price' => 0
                    ];
                }

                $attributes = MenuItemAttributes::join('menu_attributes','menu_attributes.menu_attr_id','menu_item_attributes.menu_attr_id')
                    ->whereIn('item_attr_id', $item_attr_id)
                    ->where('item_attr_id', '!=', $mainAttr->item_attr_id)
                    ->select('menu_item_attributes.attr_name as item_attr_name','menu_item_attributes.attr_price', 'menu_item_attributes.item_attr_id','menu_attributes.attr_name as menu_attr_name','menu_attributes.attr_type')
                    ->get();

                // return $attributes;    
                foreach ($attributes as $attribute) {
                    // $cartData[$stringAttrID]['attributes'][$attribute->item_attr_id] = [
                    //     'item_attr_id' => $attribute->item_attr_id,
                    //     'attr_name' => $attribute->menu_attr_name.'-'.$attribute->item_attr_name,
                    //     'attr_type' => $attribute->attr_type,
                    //     'attr_price' => $attribute->attr_price,
                    //     'quantity' => $quantity,
                    //     'attr_total_price' => ($quantity * $attribute->attr_price)
                    // ];

                    $cartData[$stringAttrID]['attributes'][] = [
                        'item_attr_id' => $attribute->item_attr_id,
                        'attr_name' => $attribute->menu_attr_name.'-'.$attribute->item_attr_name,
                        'attr_type' => $attribute->attr_type,
                        'attr_price' => $attribute->attr_price,
                        'quantity' => $quantity,
                        'attr_total_price' => ($quantity * $attribute->attr_price)
                    ];
                }
            }
        }
        if (isset($cartData[$stringAttrID]) && $cartData[$stringAttrID]['item_quantity'] == 0) {
            unset($cartData[$stringAttrID]);
        }
        
        $add_to_cart_data=TempCartTable::where('user_id', $user_id)->where('store_id', $store_id)->get()->first();
        if($add_to_cart_data)
        {
            $add_to_cart_data->attributes = json_encode($cartData );
            $add_to_cart_data->menu_id = $menu_item_id;
            $add_to_cart_data->save();
        }
        else
        {
        $tempOrder = new TempCartTable();
        $tempOrder->user_id = $user_id;
        $tempOrder->attributes = json_encode($cartData );
        $tempOrder->menu_id = $menu_item_id;
        $tempOrder->store_id = $store_id;
        $tempOrder->save();
        }
        //Session::put('cartData', $cartData); 
        //Session::save(); 
        //self::removeVoucherAuto(); 
        $temp_cart =TempCartTable::where('user_id', $user_id)->where('store_id', $store_id)->get()->first();
        if($temp_cart)
          $cartData=json_decode($temp_cart->attributes,true);
        //$cartData = Session::get ( 'cartData' );

        $tmp_cart_data = [];
        // echo "<pre>";
        // print_r($cartData);
        // die();
        // return $cartData;
        foreach($cartData as $key=>$cart_data){
            $tmp_cart_data[] = $cart_data;
            
        }

        // die("hlell");
        // return $tmp_cart_data;
        // die();
        
             

        if (empty($tmp_cart_data)) {
            Session::put('delivery_pickup_address', []);
            Session::save();
            $response = [
                'count' => count($tmp_cart_data),
                'status' => 'success',
                'message' => 'Cart empty now. Please add item to cart.',
                'cartHtml' => ''
            ];
        } else {

            // if ($item_page == 'checkoutPage') {
            //     ob_start();
            //     // echo view('Estore.StoreCheckout');
            //     $cartHtml = ob_get_clean();           
            // } else {
            //     //$cartHtml = getCartHtml(true);
            //     return json_encode('tyasdgas');
            // }
            if ($item_page == 'cartPage' || $item_page == 'checkoutPage') {
                $response = [
                    'count' => count($tmp_cart_data),
                    'status' => 'success',
                    'message' => 'Cart item updated successfully.',
                    //'cartHtml' => $cartHtml
                ];
            } else {
                $response = [
                    'count' => count($tmp_cart_data),
                    'status' => 'success',
                    'message' => 'Item add to cart successfully.',
                    'cartHtml' => $tmp_cart_data
                ];
            }
        }
        
        echo json_encode($response);
        die();  
    }
    
    public function addTipCart(Request $request)
    {
        $price = $request->input('price');
        $delivery_pickup_address = Session::get('delivery_pickup_address');
        $cartData = Session::get ( 'cartData' );
        if (empty($delivery_pickup_address) && !is_array($delivery_pickup_address)) {
            $response = [
                'count' => count($cartData),
                'status' => 'warning',
                'message' => 'Something went wrong, Please try after sometime.',
                'cartHtml' => $cartHtml
            ];
            echo json_encode($response);
            die;
        }
        $delivery_pickup_address['tipPrice'] = $price;
        Session::put('delivery_pickup_address', $delivery_pickup_address);
        Session::save();
        self::removeVoucherAuto();
        ob_start();
            echo view('Estore.StoreCheckout');
        $cartHtml = ob_get_clean();

        $response = [
            'count' => count($cartData),
            'status' => 'success',
            'message' => 'Tip added successfully.',
            'cartHtml' => $cartHtml
        ];
        echo json_encode($response);
        die; 
    }
    public function removeTipCart(Request $request)
    {
        $delivery_pickup_address = Session::get('delivery_pickup_address');
        $cartData = Session::get ( 'cartData' );
        if (empty($delivery_pickup_address) && !is_array($delivery_pickup_address)) {
            $response = [
                'count' => count($cartData),
                'status' => 'warning',
                'message' => 'Something went wrong, Please try after sometime.',
                'cartHtml' => $cartHtml
            ];
            echo json_encode($response);
            die;
        }
        $message = 'Tip added successfully.';
        $status = 'success';
        if (isset($delivery_pickup_address['tipPrice'])) {
            unset($delivery_pickup_address['tipPrice']);
            $message = 'Tip removed successfully.';
            $status = 'warning';
        }
        Session::put('delivery_pickup_address', $delivery_pickup_address);
        Session::save();
        self::removeVoucherAuto();
        ob_start();
            echo view('Estore.StoreCheckout');
        $cartHtml = ob_get_clean();

        $response = [
            'count' => count($cartData),
            'status' => $status,
            'message' => $message,
            'cartHtml' => $cartHtml
        ];
        echo json_encode($response);
        die; 
    }
    public function deleteFromCart(Request $request)
    {
        $menu_item_id = $request->input('menu_item_id');
        $quantity = $request->input('quantity');
        $item_page = $request->input('item_page');
        $store_id = $request->input('store_id');

        $menuItem = MenuItems::where('store_id', $store_id)->where('menu_item_id', $menu_item_id)->get()->first();
        $cartData = Session::get ( 'cartData' );
        if (empty($cartData)) {
            $cartData = [];
        }
        if (!$menuItem) {
            $response = [
                'count' => count($cartData),
                'status' => 'warning',
                'message' => 'Something went wrong please try after sometime'
            ];
            echo json_encode($response);
            die;
        } 
        if (isset($cartData[$menu_item_id])) {
            unset($cartData[$menu_item_id]);
        }
        Session::put('cartData', $cartData); 
        Session::save();  
        self::removeVoucherAuto();
        $cartData = Session::get ( 'cartData' );
        $cartHtml = getCartHtml(true);
        $response = [
            'count' => count($cartData),
            'status' => 'success',
            'message' => 'Item deleted successfully.',
            'cartHtml' => $cartHtml
        ];
        echo json_encode($response);
        die;
    }
    public function addFieldsCart(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');
        $delivery_pickup_address = Session::get('delivery_pickup_address');
        $delivery_pickup_address[$field] = $value;
        Session::put('delivery_pickup_address', $delivery_pickup_address); 
        Session::save();
    }
    public function clearCart(Request $request)
    {
        $store_id = $request->input('store_id');
        $currentUser = getApiCurrentUser();
        $user_id = $currentUser->user_id ? ($currentUser->user_id) : null;
        $temp_cart =TempCartTable::where('user_id', $user_id)->where('store_id', $store_id)->get()->first();
        if($temp_cart)
        {
            $temp_cart->delete();
        }
        $response = [
            'count' => 0,
            'status' => 'success',
            'message' => 'Item deleted successfully.',
            'cartHtml' => ''
        ];
        echo json_encode($response);
        die;
    }
    public function applyCouponCode(Request $request)
    {
        $voucher = Vouchers::where('code', $request->input('couponCode'))->get()->first();
        if (!$voucher) {
            $response = [
                'status' => 'false',
                'message' => 'Promo Code is invalid.'
            ];
            echo json_encode($response);
            die;
        }
        $currentUser = getCurrentUser();
        $userTags = maybe_decode($voucher->user_tags);
        if (is_array($userTags) && !in_array($currentUser->user_id, $userTags)) {
            $response = [
                'status' => 'false',
                'message' => 'You are not authorized for this promo code'
            ];
            echo json_encode($response);
            die;
        }
        $locationIds = maybe_decode($voucher->location);
       
        $weekOfDay = maybe_decode($voucher->week_of_day);
        if (is_array($weekOfDay) && !in_array(date('D'), $weekOfDay)) {
            $response = [
                'status' => 'false',
                'message' => 'Promo code is not valid for '. date('D')
            ];
            echo json_encode($response);
            die;
        }
        if ($voucher->usage_many == 'Single') {
            if (ProductOrder::where('coupon', $voucher->code)->where('user_id', $currentUser->user_id)->get()->first()) {
                $response = [
                    'status' => 'false',
                    'message' => 'You already applied for this Promo code.'
                ];
                echo json_encode($response);
                die;
            }
        } else if ($voucher->usage_many == 'Multiple') {
            if (ProductOrder::where('coupon', $voucher->code)->where('user_id', $currentUser->user_id)->count() >= $voucher->usage_many_multiple) {
                $response = [
                    'status' => 'false',
                    'message' => 'You already applied for this Promo code.'
                ];
                echo json_encode($response);
                die;
            }
        }
        $cartDatas = Session::get ( 'cartData' );
        $delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
        if (empty($cartDatas) || empty($delivery_pickup_address)) {
            $response = [
                'status' => 'false',
                'message' => 'Something went wrong, Please try after sometime'
            ];
            echo json_encode($response);
            die;
        }
        if ($voucher->usage_for != 'Both' && $voucher->usage_for != $delivery_pickup_address['order_type']) {
            $response = [
                'status' => 'false',
                'message' => 'Promo code is not valid for '. $delivery_pickup_address['order_type'] . ' order'
            ];
            echo json_encode($response);
            die;
        }
        if ($voucher->usage_for == 'Delivery' && count($locationIds) > 0) {
            $store_id = array_column($cartDatas, 'store_id');
            $store_id = reset($store_id);
            $locations = \App\StoreDeliveryLocationPrice::whereIn('store_delivery_location_id', $locationIds)->where('store_id', $store_id)->get();
            $address = [];
            foreach ($locations as $location) {
                $address[] = $location->postal_code;
            }
            $delivery_pickup_address['pincode'] = (isset($delivery_pickup_address['pincode'])?$delivery_pickup_address['pincode']:'');
            if (!in_array($delivery_pickup_address['pincode'], $address)) {
                $response = [
                    'status' => 'false',
                    'message' => 'Promo code is not vlid for this location'
                ];
                echo json_encode($response);
                die;
            }
        }
        if (($voucher->start_date && $voucher->expiry_date) && (date('Y-m-d') < $voucher->start_date || date('Y-m-d') > $voucher->expiry_date)) {
            $response = [
                'status' => 'false',
                'message' => 'Promo code is expiry.'
            ];
            echo json_encode($response);
            die;
        } 
        if (($voucher->start_time && $voucher->expiry_time) && (date('His') < date('His', strtotime($voucher->start_time)) || date('His') > date('His', strtotime($voucher->expiry_time)))) {
            $response = [
                'status' => 'false',
                'message' => 'Promo code is expiry.'
            ];
            echo json_encode($response);
            die;
        }
        $cartData = Session::get ( 'cartData' );
        $subTotal = 0; 
        $cartItemCats = [];
        foreach ($cartDatas as $cartData) {
            $menuItem = \App\MenuItems::where('menu_item_id', $cartData['menu_item_id'])->where('store_id', $cartData['store_id'])->get()->first();
            $cartItemCats[] = $menuItem->item_category;
            $subTotal += $cartData['item_total_price'];
            if (isset($cartData['attributes'])) {
                foreach ($cartData['attributes'] as $attribute) {
                    if ($attribute['attr_type'] == 'remove') {
                        $subTotal -= $attribute['attr_total_price'];
                    } else{
                        $subTotal += $attribute['attr_total_price'];
                    }
                }
            }
        }
        /*if ($voucher->category_id && is_array($cartItemCats) && in_array($voucher->category_id, $cartItemCats)) {
            $catName = MenuItemsCategory::where('item_cat_id', $voucher->category_id)->get()->pluck('cat_name')->first();
            $response = [
                'status' => 'false',
                'message' => 'You can not apply promo code on that '.$catName.'`s Items',
            ];
            echo json_encode($response);
            die;
        }*/
        if ($voucher->min_order > 0 && $voucher->min_order > $subTotal) {
            $response = [
                'status' => 'false',
                'message' => 'Your order is less then '. $voucher->min_order
            ];
            echo json_encode($response);
            die;
        }  
        $delivery_pickup_address['couponCode'] = $voucher->code;
        $delivery_pickup_address['couponType'] = 'voucher';
        Session::put ( 'delivery_pickup_address', $delivery_pickup_address );
        Session::save();
        ob_start();
            echo view('Estore.StoreCheckout');
        $cartHtml = ob_get_clean();
        $response = [
            'count' => count($cartData),
            'status' => 'success',
            'message' => 'Promo code applied.',
            'cartHtml' => $cartHtml
        ];
        echo json_encode($response);
        die;
    }
    public function removeCouponCode()
    {
        $cartData = Session::get ( 'cartData' );
        $delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
        if (empty($cartData) || empty($delivery_pickup_address)) {
            $response = [
                'status' => 'false',
                'message' => 'Something went wrong, Please try after sometime'
            ];
            echo json_encode($response);
            die;
        }
        $delivery_pickup_address['couponCode'] = '';
        $delivery_pickup_address['deal_id'] = '';
        $delivery_pickup_address['couponType'] = '';
        Session::put ( 'delivery_pickup_address', $delivery_pickup_address );
        Session::save();
        ob_start();
            echo view('Estore.StoreCheckout');
        $cartHtml = ob_get_clean();
        $response = [
            'count' => count($cartData),
            'status' => 'success',
            'message' => 'Promo code removed.',
            'cartHtml' => $cartHtml
        ];
        echo json_encode($response);
        die;
    }
    public function removeVoucherAuto()
    {
        $delivery_pickup_address = Session::get ( 'delivery_pickup_address' ); 
        if (!isset($delivery_pickup_address['couponCode']) && !isset($delivery_pickup_address['couponType'])) {
            return;
        }
        if (isset($delivery_pickup_address['couponType']) && $delivery_pickup_address['couponType'] == 'voucher') {
            $voucher = Vouchers::where('code', (isset($delivery_pickup_address['couponCode'])?$delivery_pickup_address['couponCode']:''))->get()->first();
        } else {
            $voucher = Deals::where('deal_id', $delivery_pickup_address['deal_id'])->get()->first();
        } 
        if ($voucher) {
            $cartDatas = Session::get ( 'cartData' );
            $subTotal = 0; 
            foreach ($cartDatas as $cartData) {
                $menuItem = \App\MenuItems::where('menu_item_id', $cartData['menu_item_id'])->where('store_id', $cartData['store_id'])->get()->first();
                $subTotal += $cartData['item_total_price'];
                if (isset($cartData['attributes'])) {
                    foreach ($cartData['attributes'] as $attribute) {
                        if ($attribute['attr_type'] == 'remove') {
                            $subTotal -= $attribute['attr_total_price'];
                        } else{
                            $subTotal += $attribute['attr_total_price'];
                        }
                    }
                }
            }
            if ($voucher->min_order > 0 && $voucher->min_order > $subTotal) {
                $delivery_pickup_address['couponCode'] = '';
                $delivery_pickup_address['deal_id'] = '';
                $delivery_pickup_address['couponType'] = '';
                Session::put ( 'delivery_pickup_address', $delivery_pickup_address );
                Session::save();
            }
        }        
    }
    public function applyDeal(Request $request)
    {
        $deal = Deals::where('deal_id', $request->input('dealID'))->get()->first();
        echo json_encode(self::applyAutoDeal($deal));        
        die;
    }   
    public static function applyAutoDeal($deal)
    {
        if (!$deal) {
            $response = [
                'status' => 'false',
                'message' => 'Deal is invalid.'
            ];
            return $response;
        }
        $cartDatas = Session::get ( 'cartData' );
        $delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
        if (empty($cartDatas) || empty($delivery_pickup_address)) {
            $response = [
                'status' => 'false',
                'message' => 'Something went wrong, Please try after sometime'
            ];
            return $response;
        }
        $currentUser = getCurrentUser();
        if (in_array($deal->deal_type, ['BGF', 'FI'])) {
            $menu_item_id = array_column($cartDatas, 'menu_item_id');
            $menuItemID = $deal->buy_item;
            if ($deal->deal_type == 'FI') {
                $menuItemID = $deal->menu_item_id;
            }
            if (!in_array($menuItemID, $menu_item_id)) {
                $response = [
                    'status' => 'false',
                    'message' => 'Deal only apply on '.\App\MenuItems::where('menu_item_id', $menuItemID)->get()->pluck('item_name')->first().' Item'
                ];
                return $response;
            }
        }
        if ($deal->deal_type == 'BGF') {
            $itemQTY = 0;
            foreach ($cartDatas as $cartData) {
                if ($cartData['menu_item_id'] == $deal->buy_item) {
                    $itemQTY = $cartData['item_quantity'];
                }
            }
            if ($itemQTY < $deal->buy_item_qnty) {
                $response = [
                    'status' => 'false',
                    'message' => 'Deal only apply on '.\App\MenuItems::where('menu_item_id', $deal->buy_item)->get()->pluck('item_name')->first().' Item and quantity is greater then or equal to '.$deal->buy_item_qnty
                ];
                return $response;
            }
        }
        
        if ($deal->deal_type == 'FOD' && ProductOrder::where('coupon', $deal->deal_id)->where('user_id', $currentUser->user_id)->count() == 1) {
            $response = [
                'status' => 'false',
                'message' => 'Deal already applied.'
            ];
            return $response;
        } else if ($deal->deal_type == 'POD' && $delivery_pickup_address['order_type'] != 'Pickup') {
            $response = [
                'status' => 'false',
                'message' => 'Deal is not valid for Delivery Order.'
            ];
            return $response;
        } else if ($deal->deal_type == 'DOD' && $delivery_pickup_address['order_type'] != 'Delivery') {
            $response = [
                'status' => 'false',
                'message' => 'Deal is not valid for Pickup Order.'
            ];
            return $response;
        }
        $weekOfDay = maybe_decode($deal->week_of_day);
        if (is_array($weekOfDay) && !in_array(date('D'), $weekOfDay)) {
            $response = [
                'status' => 'false',
                'message' => 'Promo code is not valid for '. date('D')
            ];
            return $response;
        }
        $locationIds = maybe_decode($deal->location);
        if (is_array($locationIds) && count($locationIds) > 0) {
            $store_id = array_column($cartDatas, 'store_id');
            $store_id = reset($store_id);
            $locations = \App\StoreDeliveryLocationPrice::whereIn('store_delivery_location_id', $locationIds)->where('store_id', $store_id)->get();
            $address = [];
            foreach ($locations as $location) {
                $address[] = $location->postal_code;
            }
            $delivery_pickup_address['pincode'] = (isset($delivery_pickup_address['pincode'])?$delivery_pickup_address['pincode']:'');
            if (!in_array($delivery_pickup_address['pincode'], $address)) {
                $response = [
                    'status' => 'false',
                    'message' => 'Deal is not valid for this location'
                ];
                return $response;
            }
        }
        if (($deal->start_date && $deal->end_date) && (date('Y-m-d') < $deal->start_date || date('Y-m-d') > $deal->end_date)) {
            $response = [
                'status' => 'false',
                'message' => 'Deal Date is expiry.'
            ];
            return $response;
        } 
        if (($deal->start_time && $deal->end_time) && (date('His') < date('His', strtotime($deal->start_time)) || date('His') > date('His', strtotime($deal->end_time)))) {
            $response = [
                'status' => 'false',
                'message' => 'Deal Time is expiry.'
            ];
            return $response;
        }
        $subTotal = 0; 
        $cartItemCats = [];
        foreach ($cartDatas as $cartData) {
            $menuItem = \App\MenuItems::where('menu_item_id', $cartData['menu_item_id'])->where('store_id', $cartData['store_id'])->get()->first();
            $cartItemCats[] = $menuItem->item_category;
            $subTotal += $cartData['item_total_price'];
            if (isset($cartData['attributes'])) {
                foreach ($cartData['attributes'] as $attribute) {
                    if ($attribute['attr_type'] == 'remove') {
                        $subTotal -= $attribute['attr_total_price'];
                    } else{
                        $subTotal += $attribute['attr_total_price'];
                    }
                }
            }
        }
        /*if ($deal->category_id && is_array($cartItemCats) && in_array($deal->category_id, $cartItemCats)) {
            $catName = MenuItemsCategory::where('item_cat_id', $deal->category_id)->get()->pluck('cat_name')->first();
            $response = [
                'status' => 'false',
                'message' => 'You can not apply Deal on that '.$catName.'`s Items',
            ];
            echo json_encode($response);
            die;
        }*/
        if ($deal->min_order > 0 && $deal->min_order > $subTotal) {
            $response = [
                'status' => 'false',
                'message' => 'Your order is less then '. $deal->min_order
            ];
            return $response;
        }  
        $delivery_pickup_address['couponCode'] = $deal->deal_title;
        $delivery_pickup_address['deal_id'] = $deal->deal_id;
        $delivery_pickup_address['couponType'] = 'deal';
        Session::put ( 'delivery_pickup_address', $delivery_pickup_address );
        Session::save();
        ob_start();
            echo view('Estore.StoreCheckout');
        $cartHtml = ob_get_clean();
        $response = [
            'count' => count($cartDatas),
            'status' => 'success',
            'message' => 'Deal applied.',
            'cartHtml' => $cartHtml
        ];
        return $response;
    }
    public function verifyAddToCart()
    {
        $cartData = Session::get ( 'cartData' );
        // echo "Gaurav.<pre>";
        // print_r($cartData);
        // die;
        $delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
        if (empty($cartData) || empty($delivery_pickup_address)) {
            return Response()->json(['status'=>'warning', 'message' => 'Your session is expired please try again.'], 200);
        }
        // die('success2');
        return Response()->json(['status'=>'success', 'message' => 'Item add to cart successfully.', 'count' => count($cartData), 'cartData' => $cartData ], 200);
    }
    public function processOrder(Request $request)
    {
        // echo "<pre>";
        // print_r(session()->all());
        // die();
        $validator = Validator::make($request->all(), self::storeRules($request));
        if ($validator->fails()) {
            // $response = [
            //     'status' => 'warning',
            //     'message' => $validator->getMessageBag()->first(),
            //     'cartHtml' => ''
            // ];
            // echo json_encode($response);
            // die;
            return Response()->json(['status'=>'warning', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],401);
        }
        if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL))
        {
            // $response = [
                //     'status' => 'warning',
                //     'message' => "The email must be a valid email address.",
                //     'cartHtml' => ''
                // ];
                // echo json_encode($response);
                // die;
            return Response()->json(['status'=>'warning', 'message' => 'The email must be a valid email address.', 'response' => [] ],401);
        }
        if(empty(PhoneOtpVerification::where('phone', $request->input('phone'))->where('otp_status', 1)->where('otp_for', 'phone_number')->get()->first()))
        {
            $otp = phoneOtpSendVarification($request->input('phone'));
            // $response = [
            //     'status' => 'warning',
            //     'showOtp' => true,
            //     'message' => 'Please verify your phone, Otp sent .'.$otp
            // ];
            // echo json_encode($response);
            // die;
            return Response()->json(['status'=>'warning', 'showOtp' => true, 'message' => 'Please verify your phone, Otp sent .'.$otp, 'response' => [] ],401);
        }
        $delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
        $delivery_pickup_address['name'] = $request->input('name');
        $delivery_pickup_address['phone'] = $request->input('phone');
        $delivery_pickup_address['email'] = $request->input('email');
        $delivery_pickup_address['special_instructions'] = $request->input('special_instructions');
        $delivery_pickup_address['accpet_term_condition'] = $request->input('accpet_term_condition');
        Session::put('delivery_pickup_address', $delivery_pickup_address); 
        Session::save();
        // $response = [
        //     'status' => 'success',
        //     'message' => ''
        // ];
        // echo json_encode($response);
        // die;
        return Response()->json(['status'=>'success', 'message' => ''.$otp, 'response' => [] ],200);
    }
    public static function generateOrder()
    {
        $cartDatas = Session::get ( 'cartData' );
        $delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
        if (!empty($cartDatas) && !empty($delivery_pickup_address)) {
            unset($delivery_pickup_address['submit']);
            $delivery_pickup_address['special_instructions'] = $delivery_pickup_address['special_instructions'];
            $subTotal = 0; 
            $catVDExpertPrice = 0;
            $currentUser = getCurrentUser();
            $store_id = 0;
            $products = [];
            $couponCode = (isset($delivery_pickup_address['couponCode'])?$delivery_pickup_address['couponCode']:'');
            $couponType = (isset($delivery_pickup_address['couponType'])?$delivery_pickup_address['couponType']:'');
            $deal_id = (isset($delivery_pickup_address['deal_id'])?$delivery_pickup_address['deal_id']:'');
            if ($couponType == 'deal') {
                $voucher = \App\Deals::where('deal_id', $deal_id)->get()->first();
            } else {
                $voucher = \App\Vouchers::where('code', $couponCode)->get()->first();
            }
            foreach ($cartDatas as $cartData) {
                $store_id = $cartData['store_id'];
                $menuItem = \App\MenuItems::where('menu_item_id', $cartData['menu_item_id'])->where('store_id', $cartData['store_id'])->get()->first()->toArray();
                $products[] = $menuItem;
                $menuItem = (object)$menuItem;
                $cartData['menuItem'] = $products;
                                           
                if ($voucher && (($voucher->category_id && $menuItem->item_category == $voucher->category_id) || (isset($voucher->menu_item_id) && $voucher->menu_item_id && $voucher->menu_item_id == $menuItem->menu_item_id)) || $menuItem->is_non_discountAble == 1) {
                  $catVDExpertPrice += $cartData['item_total_price'];
                } else {
                  $subTotal += $cartData['item_total_price'];
                }
                
                if (isset($cartData['attributes'])) {
                    foreach ($cartData['attributes'] as $attribute) {
                        if ($attribute['attr_type'] == 'remove') {
                            $subTotal -= $attribute['attr_total_price'];
                            $price_type = '-';
                        } else{ 
                            if ($voucher && (($voucher->category_id && $menuItem->item_category == $voucher->category_id) || (isset($voucher->menu_item_id) && $voucher->menu_item_id && $voucher->menu_item_id == $menuItem->menu_item_id)) || $menuItem->is_non_discountAble == 1) {
                              $catVDExpertPrice += $attribute['attr_total_price'];
                            } else {
                              $subTotal += $attribute['attr_total_price'];
                            }
                        }
                    }
                }
            }
                        
            $discount = 0;
            if ($couponType == 'deal' && $voucher) {
                if (in_array($voucher->deal_type, ['FOD','POD','DOD']) && $voucher->discount > 0) {
                  $discount = ($subTotal*$voucher->discount/100);
                  if ($discount > $voucher->max_discount && $voucher->max_discount > 0) {
                    $discount = $voucher->max_discount;
                  }
                  $subTotal = $subTotal - $discount;
                } 
            } else {
                if ($voucher && $voucher->discount > 0) {
                        if ($voucher->discount_type == 'Percentage') {
                            $discount = ($subTotal*$voucher->discount/100);
                        if ($discount > $voucher->max_discount && $voucher->max_discount > 0) {
                            $discount = $voucher->max_discount;
                        }
                        $subTotal = $subTotal - $discount;
                    } else {
                        $discount = $voucher->discount;
                        if ($discount > $voucher->max_discount) {
                            $discount = $voucher->max_discount;
                        }
                        $subTotal = $subTotal - $discount;
                    }                        
                }
            }
            $subTotal += $catVDExpertPrice;
            $store = \App\Stores::where('store_id', $store_id)->get()->first();
            $random = rand(000000, 999999);
            $order = new ProductOrder();
            $order->user_id = ($currentUser->user_id?$currentUser->user_id:0);
            $order->store_id = $store_id;
            $order->name = $delivery_pickup_address['name'];
            $order->email = $delivery_pickup_address['phone'];
            $order->phone = $delivery_pickup_address['email'];
            $order->accpet_term_condition = ($delivery_pickup_address['accpet_term_condition']?1:0);
            $order->attributes = maybe_encode($cartDatas);
            $order->product_detail = maybe_encode($products);
            $order->sub_total = $subTotal;
            if ($couponType == 'deal') {
                $order->coupon = $couponCode;
            } else {
                $order->coupon = $deal_id;
            }
            
            $order->discount = $discount;
            $order->coupon_data = $voucher;
            $order->coupon_type = $couponType;
            if ($store->store_enable_sur_charge == 'yes' && !empty($store->store_sur_charges)) {
                $sur_charge = ($subTotal*$store->store_sur_charges/100);
                $subTotal = $subTotal+$sur_charge;
                $order->sur_charge = $sur_charge;
                $order->sub_total_with_surcharge = $subTotal;
            } else {
                $order->sur_charge = 0;
                $order->sub_total_with_surcharge = 0;
            }
            $minimumOrderPrice = (isset($delivery_pickup_address['minimumOrderPrice'])?$delivery_pickup_address['minimumOrderPrice']:0);
            if ($subTotal < $minimumOrderPrice) {
                $order->extra_charges = $minimumOrderPrice-$subTotal;
                $subTotal += $minimumOrderPrice-$subTotal;
            } else {
                $order->extra_charges = 0;
            }
            if ($delivery_pickup_address['order_type'] == 'Delivery') {
                if ($couponType == 'deal' && $voucher && in_array($voucher->deal_type, ['FD'])) {
                    $order->delivery_price = 0;
                } else if ($voucher && $voucher->free_delivery == 1) {
                    $order->delivery_price = 0;
                } else {
                    $order->delivery_price = $delivery_pickup_address['pickDeliveryPrice'];
                    $subTotal += $delivery_pickup_address['pickDeliveryPrice'];
                }
            } else {
                $order->delivery_price = 0;
            }
            if (isset($delivery_pickup_address['tipPrice'])) {
                $order->tip_price = $delivery_pickup_address['tipPrice'];
                $subTotal += $delivery_pickup_address['tipPrice'];
            }
            $order->total = $subTotal;
            if ($store->store_enable_tax == 'yes' && !empty($store->store_tax)) {
                $taxPrice = $subTotal * $store->store_tax / 100;
                $order->tax = $taxPrice;
                $subTotal += $taxPrice;
            } else {
                $order->tax = 0;
            }
            $order->grand_total = $subTotal;
            $order->billing_address = maybe_encode($delivery_pickup_address);
            $order->shipping_address = maybe_encode($delivery_pickup_address);
            $order->created_at = new DateTime;
            $order->updated_at = new DateTime;
            $order->save();
            $order->transaction_id = $random.$order->order_id;
            $order->save();   
            return [
                'transaction_id' => $order->transaction_id,
                'status' => 'success'
            ];
        } else {
            return [
                'status' => 'warning',
                'message' => 'Your session is expired please try again.'
            ];
        }
    }
    public function completeFposCod($getway = null)
    {
        $response = $this->generateOrder();
        if ($response['status'] == 'warning') {
            // Session::flash('warning', $response['message']);
            // return Redirect()->back();  
            return Response()->json(['status'=>'warning', 'message' => $response['message'] ],401);     
        }
        $transaction_id = $response['transaction_id'];
        $order = ProductOrder::where('transaction_id', $transaction_id)->get()->first();

        if (empty($order)) {
            // Session::flash('warning', 'Something went wrong, Please try again. Your Payment will be refund soon.');
            // return Redirect::back();
            return Response()->json(['status'=>'warning', 'message' => 'Something went wrong, Please try again. Your Payment will be refund soon.' ],401);
        }


        $store_id = $order->store_id;
        $store = \App\Stores::where('store_id', $store_id)->get()->first();
        $device_token = \App\DeviceToken::where('user_id', $store->user_id)->get();

        $tokens = [];
		foreach($device_token as $device_data){
			$tokens[] = $device_data->token;
		}

		$token_data = [];
		$token_data["title"] = 'You have received an order';
		$token_data["body"] = 'You have received an order';
		$token_data["device_token"] = $tokens;
        $token_data["order_id"] = $order->order_id;
        $token_data["name"] = $order->name;
        $token_data["grand_total"] = $order->grand_total;
        $billing_address = maybe_decode($order->billing_address);
        $token_data["order_type"] = $billing_address['order_type'];
        $token_data["pickup_when"] = $billing_address['pickup_when'];
        // echo '<pre>';
        // print_r($token_data);
        // die();
		$this->sendPushNotification($token_data);

       
        $order->payment_getway = $getway;
        $order->save();

        Session::forget('cartData');
        Session::forget('delivery_pickup_address');  
        Session::save();              

        $emailTo = $order->email;
        $emailSubject = 'Order Placed';

        $order->attributes = maybe_decode($order->attributes);
        $order->product_detail = maybe_decode($order->product_detail);
        $order->billing_address = maybe_decode($order->billing_address);
        $order->shipping_address = maybe_decode($order->shipping_address);

        $emailBody = view('Email.OrderPlaced', compact('order'));

        SendEmail($emailTo, $emailSubject, $emailBody, [], '', '', '', '');

        SendEmail(adminEmail(), $emailSubject, $emailBody, [], '', '', '', '');

        // Session::flash('success', 'Your order placed successfully.');
        $url = url('thank-you').'?transaction_id='.$transaction_id;
        return Response()->json(['status'=>'success', 'message' => 'Your order placed successfully.', 'response' => compact('url') ],200);
        
    }

    public function sendPushNotification($data)
    {
		$url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = env('FIREBASE_SERVER_KEY');
        $title = $data['title'];
        $orderId = $data['order_id'];
        $name = $data['name'];
        $grandTotal = $data['grand_total'];
        $pickupWhen = $data['pickup_when'];
        $orderType = $data['order_type'];
        $body = $data['body'];
        $notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' => '1');
       
        $data_type = array('orderId'=>$orderId, 'name'=>$name, 'grand-total' => $grandTotal, 'pickup_when' =>$pickupWhen, 'order_type' => $orderType);
       
        $tokens = $data['device_token'];
        foreach($tokens as $token){
            $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high','data'=>$data_type);
            $json = json_encode($arrayToSend);
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key='. $serverKey;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
            $response = curl_exec($ch);
			//print_r($response);
            /* echo $response;
            if ($response === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
            } */
            curl_close($ch);
        }

	}


}

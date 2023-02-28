<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

class EstoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function orderOnline()
    {   
        if (Request()->get('type') == 'new') {
            Session::put('cartData', []); 
            Session::put('delivery_pickup_address', []); 
            Session::save();
        }
        $cartDatas = Session::get ( 'cartData' );
        $delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
        $view = 'Estore.OrderOnline';
        $title = 'Delivery Details | Maharaja Hotel';
        return view('OrderFront', compact('view','title','cartDatas','delivery_pickup_address'));
    }
    public function index($type = null)
    {    
        if ($type == 'delivery') {
            $view = 'Estore.Delivery';
            $title = 'Delivery Details | Maharaja Hotel';
        } else if ($type == 'pickup') {
            $view = 'Estore.Pickup';
            $title = 'Pick Up details | Maharaja Hotel';
        }
        return view('OrderFront', compact('view','title','type'));
    }  
    public function searchStore(Request $request)
    {
        $keyword = $request->input('term');
        $rows = [];
        if (empty($keyword)) {
            $rows[] = [
                'value' => '',
                'id' => '',
                'label' => ''
            ];
        }
        $storeIDS = StorePickupLocations::where(function($query) use($keyword){
                        if ($keyword) {
                            $query->orwhere('city', 'LIKE', '%'.$keyword.'%');
                            $query->orwhere('suburb', 'LIKE', '%'.$keyword.'%');
                            $query->orwhere('postal_code', 'LIKE', '%'.$keyword.'%');
                        }
                    })->select('store_id')->get()->pluck('store_id')->toArray();
        $stores = Stores::select('store_id','store_title','store_address','store_suburb','store_postalCode')
                    ->where(function($query) use($keyword, $storeIDS){
                        if ($keyword) {
                            $query->orwhere('store_title', 'LIKE', '%'.$keyword.'%');
                            $query->orwhere('store_address', 'LIKE', '%'.$keyword.'%');
                            $query->orwhere('store_suburb', 'LIKE', '%'.$keyword.'%');
                            $query->orwhere('store_postalCode', 'LIKE', '%'.$keyword.'%');
                        }
                        if ($storeIDS) {
                            $query->orwhereIn('store_id', $storeIDS);
                        }
                    })->paginate(pagination());
        foreach ($stores as $store) {
            $rows[] = [
                'label' => $store->store_title.' '.$store->store_postalCode.' '.$store->store_suburb.' '.$store->store_address,
                'value' => $store->store_title.' '.$store->store_postalCode.' '.$store->store_suburb.' '.$store->store_address,
                'id' => str_replace(' ','-',$store->store_title.'-'.$store->store_id)
            ];
        }
        return json_encode($rows);
    }
    public function pickupStore($pickup_when = 'Now', $storeSlug = null)
    {
        if (empty($storeSlug)) {
            return Redirect()->back();
        }
        $storeOriginalSlug = $storeSlug;
        $view = 'Estore.PickupStoreTiming';
        $title = str_replace('-',' ',$storeSlug).' | Maharaja Hotel';
        $storeSlug = explode('-', $storeSlug);
        $store_id = end($storeSlug);
        $store = Stores::select('store_id','store_title','store_address','store_suburb','store_postalCode','store_city')
                    ->where('store_id', $store_id)->get()->first();
        if (!$store) {
            Session::flash ( 'warning', "Store not found on your selected location, check your postal code and suburb." );
            return Redirect()->back();
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
                return redirect($url);
            }            
        }
        
        return view('OrderFront', compact('view','title','store','pickup_when','weekdays','datesArray'));
    }
    public function getSelectedTimes(Request $request)
    {
        $currentDay = $request->input('selectedDay');
        $store_id = $request->input('store_id');
        $selectedDate = $request->input('selectedDate');
        $orderType = $request->input('orderType');
        $anotherTimings = 'StoreOnlineOrderTimingsPickup';
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
                                    $html .= '<option value="'.$open_time.'">'.date('h:i A', strtotime($open_time)).'</option>';
                                } 
                            }else{
                                $html .= '<option value="'.$open_time.'">'.date('h:i A', strtotime($open_time)).'</option>';
                            } 
                        }                         
                    } else {
                        if ($selectedDate == date('Ymd')) {
                            if ($open_time > date('His', strtotime("+".$cutOfTime." minutes"))) {
                                $html .= '<option value="'.$open_time.'">'.date('h:i A', strtotime($open_time)).'</option>';
                            } 
                        }else{
                            $html .= '<option value="'.$open_time.'">'.date('h:i A', strtotime($open_time)).'</option>';
                        }  
                    }
                                                          
                    $open_time = date('His', strtotime($open_time)+($sloteMinute*60));
                 }
              }
           }
        }
        if (!empty($html)) {
            echo '<option value="">Select Time</option>'.$html;
            die;
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
            if ($currentSlotExit == true) {
                if ($orderType == 'StoreOnlineOrderTimingsPickup') {
                    echo 'DeliveryAvailable';
                    die;
                } else if ($orderType == 'StoreOnlineOrderTimingsDelivery') {
                    echo 'PickupAvailable';
                    die;
                }
            }
        }
        echo '<option value="">Select Time</option>';
        die;
    }
    public function deliveryStore(Request $request)
    {
        $view = 'Estore.DeliveryStoreTiming';
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
            Session::flash ( 'warning', "Stores not found on your selected location, check your postal code and suburb." );
            return Redirect()->back();
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
                $url = url('estore/items').'?store='.$storeOriginalSlug.'&order_date='.date('Ymd').'&order_time='.$currentSlotExit.'&order_type=Delivery&'.$urlString;
                return redirect($url);
            } 
        }
        $title = $suburb.' - '.$pincode.' | Maharaja Hotel';
        return view('OrderFront', compact('view','title','stores','pickup_when','unit_number','city','street_number','street','suburb','pincode','lat','lng'));
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
        foreach (getDateRange(20) as $date) {
           $day = date('D', strtotime($date));
           if (isset($weekdays[$day]['status']) && $weekdays[$day]['status'] == 1) {
              if (!$selectedDate) {
                 $selectedDate = $date;
              }
              echo '<option value="'.$date.'" data-day="'.$day.'" '.$selectedAttr.'>'.date('D d M Y', strtotime($date)).'</option>';
              $selectedAttr = '';
           }                                             
        }
    }
    public function storeItems(Request $request)
    {
        $view = 'Estore.StoreItems';
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
            Session::flash ( 'warning', "Store not found on your selected location, check your postal code and suburb." );
            return Redirect()->back();
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
                    //->join('menu_items_category','menu_items_category.item_cat_id','menu_items.item_category')
                    ->select('menu_items.item_category','menu_items.menu_item_id')
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

        //return Response()->json(['status'=>'success', 'message' => 'Menu items and sub items', 'response' => compact('view','title','store','pickup_when','orderDate','orderTime','menuItems','item_display_in','item_for','is_delicous','category_name','requestPerameters','currentSlotExit','itemPage','banners','openPage') ],200);

        return view('OrderFront', compact('view','title','store','pickup_when','orderDate','orderTime','menuItems','item_display_in','item_for','is_delicous','category_name','requestPerameters','currentSlotExit','itemPage','banners','openPage'));
    }
    public function getMenuItemAttributes(Request $request)
    {
        $store_id = $request->input('store_id');
        $menu_item_id = $request->input('menu_item_id');
        $menuItem = \App\MenuItems::where('menu_item_id', $menu_item_id)->where('store_id', $store_id)->get()->first();
        
        $menuAttributes = MenuAttributes::where('store_id', $store_id)
                    ->whereIn('menu_attr_id', MenuItemAttributes::where('menu_item_id', $menu_item_id)->select('menu_attr_id')->get())
                    ->where('attr_status', 'Active')
                    ->orderBy('attr_main_choice', 'DESC')
                    ->get();

        ?>
        <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal">&times;</button>
           <div class="modal-title">Specify your Dish</div>
        </div>
        <div class="modal-body">
           <div class="menu-title">
              <div class="col-xs-8">
                 <h2><?php echo $menuItem->item_name ?></h2>
              </div>
              <div class="col-xs-4">
                 <span class="menu-price menu-price-updated" data-price="<?php echo itemShowPrice($menuItem) ?>"><?php echo priceFormat(itemShowPrice($menuItem)); ?></span>
              </div>
              <div class="col-md-12">
                <div class="alert-danger hide showMessage"></div>
              </div>
           </div>
           <div class="panel-wrap">
            <form class="itemAttributeForm">
                <input type="hidden" name="quantity" value="1">
              <div class="panel-group">
                <?php 
                $active = ' collapse in';
                $notActiveClass = '';
                $attrIndex = 0;
                foreach ($menuAttributes as $menuAttribute) {
                    $attr_nam_slug = str_replace(' ', '-', $menuAttribute->attr_name);
                    ?>
                    <div class="panel panel-default attributeClassCommon" data-class="<?php echo $attr_nam_slug; ?>">
                       <div class="panel-heading">
                          <h4 class="panel-title">
                             <a data-toggle="collapse" <?php echo $notActiveClass; ?>  data-parent="#accordion" href="#collapse_<?php echo $menuAttribute->menu_attr_id ?>">
                             <?php echo $menuAttribute->attr_name; ?>
                             </a>
                          </h4>
                       </div>
                       <div id="collapse_<?php echo $menuAttribute->menu_attr_id ?>" class="panel-collapse <?php echo $active ?>">
                         <?php 
                         $attributes = MenuItemAttributes::where('menu_item_id', $menu_item_id)->where('menu_attr_id', $menuAttribute->menu_attr_id)->orderBy('attr_price', 'ASC')->get();
                         if ($menuAttribute->attr_selection == 'multiple') {
                            $contaninerClass = 'additions';
                            $itemClass = 'container';
                            if ($menuAttribute->attr_type == 'remove') {
                                $contaninerClass = 'remove-items';
                                $itemClass = 'container cross-container';
                            }
                            $attr_selection_min_count = $menuAttribute->attr_selection_mutli_value_min;
                            $attr_selection_max_count = $menuAttribute->attr_selection_mutli_value_max;
                            ?>
                            <div class="panel-body">
                               <div class="<?php echo $contaninerClass; ?> attrSelectionCountCheck attriMandat">
                                <?php 
                                foreach ($attributes as $attribute) {
                                    $attributeClass = '';
                                    $class = "";
                                    $attributeChecked = '';
                                    $selectAbleClass = '';
                                    if ($menuAttribute->attr_main_choice == 1) {
                                        $attributeClass = 'data-class="'.$attribute->attr_size.'"';
                                        $selectAbleClass = 'selectAbleCheck';
                                    } else {
                                        $class = $attribute->attr_size.' commonAttrAll';
                                    }
                                    if ($attribute->attr_default_choice == 1) {
                                        $attributeChecked = 'checked';
                                    }
                                    ?>
                                    <input type="hidden" name="attr_type[<?php echo $attribute->item_attr_id ?>]" value="<?php echo $menuAttribute->attr_type ?>">
                                    <label class="<?php echo $itemClass; ?> itemContainer_<?php echo $attr_nam_slug; ?>" for="attr_<?php echo $attribute->item_attr_id ?>"><?php echo $attribute->attr_name ?>
                                    <input class="checkChecked checkbox_<?php echo $menuAttribute->attr_mandatory; ?> checkCheckedCheckbox <?php echo $class.' '.$selectAbleClass; ?>" <?php echo $attributeClass; ?> <?php echo $attributeChecked; ?> data-minChoice="<?php echo $attr_selection_min_count; ?>" data-maxChoice="<?php echo $attr_selection_max_count; ?>" data-attr_type="<?php echo $menuAttribute->attr_type ?>" data-attr_price="<?php echo $attribute->attr_price ?>" type="checkbox" name="item_attr_id[attr_<?php echo $attrIndex ?>][]" id="attr_<?php echo $attribute->item_attr_id ?>" value="<?php echo $attribute->item_attr_id ?>">
                                    <span class="checkmark"></span>
                                    <span class="size-amt"><?php echo ($attribute->attr_price > 0 ?priceFormat($attribute->attr_price):'') ?></span>
                                    </label>
                                    <?php
                                }
                                ?>
                               </div>
                            </div>
                            <?php
                         } else {
                            ?>
                            <div class="panel-body">
                               <div class="row">
                                  <div class="col-sm-12 attriMandat">
                                    <?php 
                                    foreach ($attributes as $attribute) {
                                        $attributeClass = '';
                                        $class = "";
                                        $attributeChecked = '';
                                        $selectAbleClass = '';
                                        if ($menuAttribute->attr_main_choice == 1) {
                                            $attributeClass = 'data-class="'.$attribute->attr_size.'"';
                                            $selectAbleClass = 'selectAbleCheck';
                                        } else {
                                            $class = $attribute->attr_size.' commonAttrAll';
                                        }
                                        if ($attribute->attr_default_choice == 1) {
                                            $attributeChecked = 'checked';
                                        }
                                        ?>
                                        <div class="radio radio-danger">
                                           <span class="size-amt"><?php echo ($attribute->attr_price > 0 ?priceFormat($attribute->attr_price):'') ?></span>
                                           <input type="radio" data-attr_type="<?php echo $menuAttribute->attr_type ?>" data-attr_price="<?php echo $attribute->attr_price ?>" <?php echo $attributeClass; ?> <?php echo $attributeChecked; ?> data-minChoice="1" class="checkChecked checkbox_<?php echo $menuAttribute->attr_mandatory; ?> <?php echo $class.' '.$selectAbleClass ?>" name="item_attr_id[attr_<?php echo $attrIndex ?>][]" id="attr_<?php echo $attribute->item_attr_id ?>" value="<?php echo $attribute->item_attr_id ?>">
                                           <label for="attr_<?php echo $attribute->item_attr_id ?>" class=" itemContainer_<?php echo $attr_nam_slug; ?>">
                                           <?php echo $attribute->attr_name ?>
                                           </label>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                  </div>
                               </div>
                            </div>
                            <?php
                            $attrIndex++;
                         }
                         ?>                         

                       </div>
                    </div>
                    <?php
                    $active = '';
                    $notActiveClass = 'class="collapsed"';
                }
                ?>
              </div>
           </div>
          </form>
        </div>
        <?php
        $item_page = $request->input('item_page');
        $attributes = ' data-store_id="'.$menuItem->store_id.'"';
        $attributes .= ' data-menu_item_id="'.$menuItem->menu_item_id.'"';
        $attributes .= ' data-item_name="'.$menuItem->item_name.'"';
        if ($item_page == 'checkoutPage') {
            $attributes .= ' data-item_page="checkoutPage"';
        } else {
            $attributes .= ' data-item_page="itemPage"';
        }
        ?>
        <div class="modal-footer">
           <button type="button" class="btn btn-default btn-cart addToCartAttributeButton" <?php echo $attributes ?>>Add To Cart</button>
        </div>
        <?php
    }
}

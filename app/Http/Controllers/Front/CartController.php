<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\Stores;
use App\MenuItems;
use App\MenuItemType;
use App\MenuItemsCategory;
use App\MenuItemAttributes;
use App\Vouchers;
use App\Deals;
use App\ProductOrder;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {        

        $menu_item_id = $request->input('menu_item_id');
        $quantity = $request->input('quantity');
        $item_page = $request->input('item_page');
        $store_id = $request->input('store_id');
        /*Session::put('cartData', []); 
        Session::save();*/ 
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
        if ($request->input('type') == 'attribute') {   
            return self::addToCartAttribute($request, $cartData, $menuItem);
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
            $response = [
                'count' => count($cartData),
                'status' => 'warning',
                'message' => 'Cart empty now. Please add item to cart.',
                'cartHtml' => ''
            ];
        } else {
            if ($item_page == 'checkoutPage') {
                ob_start();
                    echo view('Estore.StoreCheckout');
                $cartHtml = ob_get_clean();         
            } else {
                $cartHtml = getCartHtml(true);
            }

            if ($item_page == 'cartPage' || $item_page == 'checkoutPage') {
                $response = [
                    'count' => count($cartData),
                    'status' => 'success',
                    'message' => 'Cart item updated successfully.',
                    'cartHtml' => $cartHtml
                ];
            } else {
                $response = [
                    'count' => count($cartData),
                    'status' => 'success',
                    'message' => 'Item add to cart successfully.',
                    'cartHtml' => $cartHtml
                ];
            }
        }
        echo json_encode($response);
        die;
    }
    public function addToCartAttribute($request, $cartData, $menuItem)
    {  
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

        $attr_type = $request->input('attr_type');
        // echo "<pre>";
        // echo json_encode($item_attrs);
        // echo json_encode($attr_type);
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
                foreach ($attributes as $attribute) {
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
        }
        if (isset($cartData[$stringAttrID]) && $cartData[$stringAttrID]['item_quantity'] == 0) {
            unset($cartData[$stringAttrID]);
        }

        Session::put('cartData', $cartData); 
        Session::save(); 
        self::removeVoucherAuto(); 
        $cartData = Session::get ( 'cartData' );
        if (empty($cartData)) {
            Session::put('delivery_pickup_address', []);
            Session::save();
            $response = [
                'count' => count($cartData),
                'status' => 'success',
                'message' => 'Cart empty now. Please add item to cart.',
                'cartHtml' => ''
            ];
        } else {
            if ($item_page == 'checkoutPage') {
                ob_start();
                    echo view('Estore.StoreCheckout');
                $cartHtml = ob_get_clean();           
            } else {
                $cartHtml = getCartHtml(true);
            }
            if ($item_page == 'cartPage' || $item_page == 'checkoutPage') {
                $response = [
                    'count' => count($cartData),
                    'status' => 'success',
                    'message' => 'Cart item updated successfully.',
                    'cartHtml' => $cartHtml
                ];
            } else {
                $response = [
                    'count' => count($cartData),
                    'status' => 'success',
                    'message' => 'Item add to cart successfully.',
                    'cartHtml' => $cartHtml
                ];
            }
        }
        echo json_encode($response);
        die;  
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
    public function clearCart()
    {
        Session::put('cartData', []); 
        Session::save();  
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
        
        $delivery_pickup_address = Session::get ( 'delivery_pickup_address' );
        if (empty($cartData) || empty($delivery_pickup_address)) {
            die('error');
        }
        die('success2');
    }
}

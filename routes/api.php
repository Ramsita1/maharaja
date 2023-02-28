<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('v1/loginAccess', 'API\Auth\LoginController@login');
Route::post('v1/resetPassword', 'API\Auth\LoginController@resetPasswordOtpUser');
Route::get('v1/testPushNotification', 'API\Orders\OrdersController@testPushNotification');

Route::post('v1/forgotPassword', 'API\Auth\LoginController@forgotPasswordUser');
Route::post('v1/user/forgotPasswordOtpUser', 'Auth\LoginController@forgotPasswordOtpUser');
Route::post('v1/user/resetPasswordUser', 'Auth\LoginController@resetPasswordUser');

Route::post('v1/user/registerStepOne', 'Auth\RegisterController@storeStepOne');
Route::post('v1/user/registerStepTwo', 'Auth\RegisterController@storeStepTwo');
Route::post('v1/user/register', 'Auth\RegisterController@store');


Route::group(['middleware' => ['cors','staffToken'], 'prefix' => 'v1/store', 'namespace' => 'API'], function(){
	// Route::get('getOrders', 'Orders\OrdersController@getOrders');
	// Route::get('getSingleOrder/{order_id}', 'Orders\OrdersController@getSingleOrder');
	// Route::post('updateOrder', 'Orders\OrdersController@updateOrderStatus');
	// Route::get('getUserDetails', 'Users\StoreUsersController@getUserDetails');
	//Route::post('updatePaymentStatus', 'Orders\OrdersController@updatePaymentStatus'); 
	Route::get('delivery-boy', 'Orders\OrdersController@getDeliveryBoy');
	// Route::get('storeItemCat', 'MenuItems\MenuItemsController@storeItemCat');
	// Route::post('updateMenuItem', 'MenuItems\MenuItemsController@updateMenuItemStatus');
	Route::post('assignDeliveryBoy', 'Orders\OrdersController@assignDeliveryBoy');

});

// Route::group(['middleware' => ['cors','staffToken', 'employeeToken'], 'prefix' => 'v1/store', 'namespace' => 'API'], function(){
Route::group(['middleware' => ['cors','empstaffToken'], 'prefix' => 'v1/store', 'namespace' => 'API'], function(){
	//Route::post('updateOrder', 'Orders\OrdersController@updateOrderStatus');
	Route::get('getOrders', 'Orders\OrdersController@getOrders');
	Route::get('storeItemCat', 'MenuItems\MenuItemsController@storeItemCat');
	Route::post('updateMenuItem', 'MenuItems\MenuItemsController@updateMenuItemStatus');
	// Route::get('getSingleOrder/{order_id}', 'Orders\OrdersController@getSingleOrder');
	// Route::get('getUserDetails', 'Users\StoreUsersController@getUserDetails');
	Route::get('getPrinters', 'MenuItems\MenuItemsController@getPrinters');
	Route::post('addPrinter', 'MenuItem	s\MenuItemsController@addPrinter');
	Route::post('updatePrinter', 'MenuItems\MenuItemsController@updatePrinter');
	Route::post('deletePrinter', 'MenuItems\MenuItemsController@deletePrinter');
});


//Route::group(['middleware' => ['cors', 'staffToken', 'employeeToken', 'driverToken'], 'prefix' => 'v1/store', 'namespace' => 'API'], function(){
Route::group(['middleware' => ['cors', 'employeeToken'], 'prefix' => 'v1/store', 'namespace' => 'API'], function(){
	Route::post('updateOrder', 'Orders\OrdersController@updateOrderStatus');
	Route::get('getSingleOrder/{order_id}', 'Orders\OrdersController@getSingleOrder');
	Route::get('getUserDetails', 'Users\StoreUsersController@getUserDetails');
	Route::get('getSingleOrderClone/{order_id}', 'Orders\OrdersController@getSingleOrderClone');

});

Route::group(['middleware' => ['cors','driverToken'], 'prefix' => 'v1/driver', 'namespace' => 'API'], function(){
	Route::get('getOrderList', 'DeliveryBoy\DeliveryBoyController@deliveryBoyOrderList');
	Route::post('updateOrder', 'Orders\OrdersController@updateOrderStatus');
	Route::post('updatePaymentStatus', 'Orders\OrdersController@updatePaymentStatus'); 
});

Route::get('v1/menuitems', 'API\MenuItems\MenuItemsController@storeItemCat');


//user

Route::get('v1/estore/pickup/{time}/{store}', 'API\Users\EstoreController@pickupStore');
Route::get('v1/estore/getSelectedTimes', 'API\Users\EstoreController@getSelectedTimes');
Route::get('v1/estore/getStoreDates', 'API\Users\EstoreController@getStoreDates');
Route::get('v1/estore/delivery', 'API\Users\EstoreController@deliveryStore');
Route::get('v1/estore/items', 'API\Users\EstoreController@storeItems');
Route::get('v1/item/getMenuItemAttributes', 'API\Users\EstoreController@getMenuItemAttributes');
Route::post('v1/item/addToCart', 'API\Users\EstoreController@addToCart');
Route::post('v1/item/clearCart', 'API\Users\EstoreController@clearCart');
Route::get('v1/verify/add/to/cart', 'API\Users\EstoreController@verifyAddToCart');
Route::post('v1/process/order', 'API\Users\EstoreController@processOrder');
//Route::get('v1/payment/checkout', 'API\Users\EstoreController@paymentCheckout');
Route::get('v1/proceed/payment/{getway}', 'API\Users\EstoreController@completeFposCod');
Route::get('v1/strip/payment/{token}', 'API\Users\StripController@createPayment');
Route::get('v1/paywithpaypal', 'API\Users\PaypalController@payWithpaypal');
Route::get('v1/payWithpaypalcallback/{type?}', 'API\Users\PaypalController@payWithpaypalcallback');
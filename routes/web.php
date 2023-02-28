<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return redirect('/');
});

Route::resource(adminBasePath().'/login', 'Admin\Auth\LoginController', ['names' => 'login']);
Route::get('varify/email/link/{activation_key?}', 'API\Auth\RegistTrationController@verifyEmailLink');
Route::get('/getstates', function(Illuminate\Http\Request $request){
    return getState($request->input('country_name'));
});
Route::get('/getcitis', function(Illuminate\Http\Request $request){
    return getStateCity($request->input('state_name'));
});
/*********Frontend url**************/
Route::get('order/online', 'Front\EstoreController@orderOnline')->name('order.online');
Route::get('estore/service/method/{type?}', 'Front\EstoreController@index')->name('order.type');

Route::get('search/store', 'Front\EstoreController@searchStore');
Route::get('estore/pickup/{time}/{store}', 'Front\EstoreController@pickupStore');
Route::get('estore/getSelectedTimes', 'Front\EstoreController@getSelectedTimes');
Route::get('estore/getStoreDates', 'Front\EstoreController@getStoreDates');
Route::get('estore/delivery', 'Front\EstoreController@deliveryStore');
Route::get('estore/items', 'Front\EstoreController@storeItems');
Route::get('item/getMenuItemAttributes', 'Front\EstoreController@getMenuItemAttributes');
Route::post('item/addToCart', 'Front\CartController@addToCart');
Route::post('item/deleteFromCart', 'Front\CartController@deleteFromCart');
Route::post('item/clearCart', 'Front\CartController@clearCart');
Route::post('cart/add/tip', 'Front\CartController@addTipCart');
Route::post('cart/remove/tip', 'Front\CartController@removeTipCart');
Route::post('cart/add/fields', 'Front\CartController@addFieldsCart');
Route::post('applyCouponCode', 'Front\CartController@applyCouponCode');
Route::post('applyDeal', 'Front\CartController@applyDeal');
Route::post('removeCouponCode', 'Front\CartController@removeCouponCode');
Route::get('verify/add/to/cart', 'Front\CartController@verifyAddToCart');

Route::post('process/order', 'Front\OrderController@processOrder');
Route::get('payment/checkout', 'Front\FrontController@paymentCheckout');
Route::post('verify/phone', 'Front\OrderController@reVerifyPhoneOtp');
Route::post('submit/verify/phone', 'Front\OrderController@reSubmitVerifyPhoneOtp');
Route::post('verifyPhoneNO', 'Front\OrderController@verifyPhoneNO');

Route::get('proceed/payment/{getway}', 'Front\OrderController@completeFposCod');
Route::get('strip/payment/{token}', 'Front\StripController@createPayment');
Route::get('paywithpaypal', 'Front\PaypalController@payWithpaypal');
Route::get('payWithpaypalcallback/{type?}', 'Front\PaypalController@payWithpaypalcallback');

Route::post('user/login', 'Auth\LoginController@store')->name('front.login');
Route::post('user/forgotPasswordUser', 'Auth\LoginController@forgotPasswordUser')->name('front.forgotPasswordUser');
Route::post('user/forgotPasswordOtpUser', 'Auth\LoginController@forgotPasswordOtpUser')->name('front.forgotPasswordOtpUser');
Route::post('user/resetPasswordUser', 'Auth\LoginController@resetPasswordUser')->name('front.resetPasswordUser');
Route::post('user/registerStepOne', 'Auth\RegisterController@storeStepOne')->name('front.registerStepOne');
Route::post('user/registerStepTwo', 'Auth\RegisterController@storeStepTwo')->name('front.registerStepTwo');
Route::post('user/register', 'Auth\RegisterController@store')->name('front.register');
/**********Backend url***********/
Route::group(['middleware' => ['userRoleAuth']], function(){

    Route::get(adminBasePath().'/logout', 'Admin\Auth\LoginController@logout')->name('logout');
    Route::resource(adminBasePath().'/dashboard', 'Admin\Dashboard\DashboardController', ['names' => 'dashboard']);
    Route::get(adminBasePath().'/dashboard_createVoucher', 'Admin\Dashboard\DashboardController@createVoucher')->name('dashboard_createVoucher');

    Route::post(adminBasePath().'/dashboardPostVoucher', 'Admin\Dashboard\DashboardController@dashboardPostVoucher')->name('dashboardPostVoucher');

    Route::resource(adminBasePath().'/stores','Admin\Stores\StoreController');

    Route::resource(adminBasePath().'/menuItemBanner','Admin\MenuItems\MenuItemBannerController');

    Route::get(adminBasePath().'/store/showStore','Admin\Stores\StoreController@showStore')->name('store.showStore');
    Route::resource(adminBasePath().'/storesOnlineOrderTimings','Admin\Stores\StoresOnlineOrderTimingsController');
    Route::resource(adminBasePath().'/storesDeliveryLocationPrice','Admin\Stores\StoreDeliveryLocationPriceController');
    Route::resource(adminBasePath().'/storePickupLocations','Admin\Stores\StorePickupLocationsController');

    Route::resource(adminBasePath().'/menu/items','Admin\MenuItems\MenuItemsController', ['names' => 'menuItem']);

    Route::get(adminBasePath().'/insertDeletePrinter', 'Admin\MenuItems\MenuItemsController@insertDeletePrinter')->name('insertDeletePrinter');
    
    Route::get(adminBasePath().'/items/update/order', 'Admin\MenuItems\MenuItemsController@updateOrder')->name('menuItem.updateOrder');
    Route::get(adminBasePath().'/items/sortItemCat', 'Admin\MenuItems\MenuItemsController@sortItemCat')->name('menuItem.sortItemCat');
    Route::resource(adminBasePath().'/menu/attributes/item','Admin\MenuItems\MenuItemAttributesController', ['names' => 'menuItemAttributes']);
    Route::get(adminBasePath().'/menu/attributes/search','Admin\MenuItems\MenuItemAttributesController@searchattribute')->name('menuItemAttributes.searchattribute');
    Route::get(adminBasePath().'/menu/search/item','Admin\MenuItems\MenuItemAttributesController@searchMenuItem')->name('menuItemAttributes.searchMenuItem');
    Route::get(adminBasePath().'/getItemAttributes','Admin\MenuItems\MenuItemAttributesController@getItemAttributes')->name('menuItemAttributes.getItemAttributes');
    Route::get(adminBasePath().'/copyItemAttributes','Admin\MenuItems\MenuItemAttributesController@copyItemAttributes')->name('menuItemAttributes.copyItemAttributes');
    
    Route::resource(adminBasePath().'/menu/attribute','Admin\MenuItems\MenuAttributesController', ['names' => 'menuAttribute']);
    Route::get(adminBasePath().'/menuItem/getItemAttribute','Admin\MenuItems\MenuItemsController@getItemAttribute')->name('getItemAttribute');    

    Route::resource(adminBasePath().'/media', 'Admin\Media\MediaController', ['names' => 'media']);
    Route::get(adminBasePath().'/get/media', 'Admin\Media\MediaController@modal')->name('media.get');
    Route::get(adminBasePath().'/get/media/gallery', 'Admin\Media\MediaController@gallery')->name('media.gallery');
    Route::get(adminBasePath().'/delete/media/gallery', 'Admin\Media\MediaController@destroy')->name('media.delete');
    Route::get(adminBasePath().'/update/media/gallery', 'Admin\Media\MediaController@update')->name('media.updateAlt');
	
	Route::resource(adminBasePath().'/post', 'Admin\Post\PostController', ['names' => 'post','parameters' => [
    'postType' => 'postType', 'post_id' => 'post_id?']]);

    Route::get(adminBasePath().'/post/update/order', 'Admin\Post\PostController@updateOrder')->name('post.updateOrder');
    Route::get(adminBasePath().'/post/update/postName', 'Admin\Post\PostController@updatePostName')->name('post.updatePostName');

    Route::get(adminBasePath().'/get/product/attribute', function(Illuminate\Http\Request $request){
        $index = $request->input('index');
        $term = $request->input('term');
        echo getVariationGroupItem($index, $term, []);
        die;
    })->name('product.attribute');

    Route::resource(adminBasePath().'/taxonomy', 'Admin\Post\TaxonomyController', ['names' => 'taxonomy','parameters' => ['postType' => 'postType', 'taxonomy' => 'taxonomyType', 'term_id' => 'term_id?']]);

    Route::get(adminBasePath().'/taxonomy/configure/terms/{postType?}/{taxonomy?}', 'Admin\Post\TaxonomyController@configureTerms')->name('taxonomy.configureTerms');

    Route::get(adminBasePath().'/post/clone/{post_id?}', 'Admin\Post\PostController@clone')->name('post.clone');
    Route::get(adminBasePath().'/delete/all/post','Admin\Post\PostController@deleteAll')->name('post.deleteAll');
    // Menu Routes
    Route::get(adminBasePath().'/menus','Admin\Menu\MenuController@index')->name('menus');
    Route::post(adminBasePath().'/add/menu','Admin\Menu\MenuController@addMenuItems')->name('add.menu');
    Route::get(adminBasePath().'/delete/menu','Admin\Menu\MenuController@deleteMenuItems')->name('delete.menu');
    //Theme Routes
    Route::resource(adminBasePath().'/themes','Admin\Themes\ThemeController');
    // Feedback
    Route::get(adminBasePath().'/feedback','Admin\Feedback\FeedbackController@index')->name('feedback.get');
    Route::delete(adminBasePath().'/feedback/delete/{id}','Admin\Feedback\FeedbackController@destroy')->name('feedback.destroy');
    
    Route::resource(adminBasePath().'/users','Admin\Users\UsersController');    
    Route::resource(adminBasePath().'/storeUsers', 'Admin\Users\StoreUsersController', ['name' => 'storeUsers']);
    Route::resource(adminBasePath().'/storeHolidays', 'Admin\Stores\StoreHolidaysController', ['name' => 'storeHolidays']);
    Route::resource(adminBasePath().'/storesSurgeCharges', 'Admin\Stores\StoresSurgeChargesController', ['name' => 'storesSurgeCharges']);
    Route::get(adminBasePath().'/updateSurcharge','Admin\Stores\StoresSurgeChargesController@updateSurcharge')->name('storesSurgeCharges.updateSurcharge');

    /******Product Orders******/
    Route::resource(adminBasePath().'/orders','Admin\ProductOrder\ProductOrderController');
    Route::resource(adminBasePath().'/menu/item/category','Admin\MenuItems\MenuItemsCategoryController', ['names' => 'menuItemCategory']);
    Route::get(adminBasePath().'/category/update/order', 'Admin\MenuItems\MenuItemsCategoryController@updateOrder')->name('menuItemCategory.updateOrder');

    Route::resource(adminBasePath().'/menu/size/attribute','Admin\MenuItems\MenuAttributeSizeController', ['names' => 'menuAttributeSize']);
    Route::resource(adminBasePath().'/deals','Admin\Deals\DealsController');
    Route::get(adminBasePath().'/getStoreCategory','Admin\Deals\DealsController@getStoreCategory')->name('deals.getStoreCategory');
    Route::resource(adminBasePath().'/vouchers','Admin\Vouchers\VouchersController');

    Route::resource(adminBasePath().'/siteMap','Admin\SiteMap\SiteMapController');

    Route::resource(adminBasePath().'/comment','Admin\Comment\CommentController');
    Route::get(adminBasePath().'/delete/all/comment','Admin\Comment\CommentController@deleteAll')->name('comment.deleteAll');

});

Route::get('/', 'Front\FrontController@index');
Route::get('/{slug?}', 'Front\FrontController@single');
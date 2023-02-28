<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItems extends Model
{
	/**Table Name**/
	protected $table = 'menu_items';
    /**Primary Key**/
    protected $primaryKey = 'menu_item_id';
	/**Fields**/
    protected $fillable = ['store_id','item_name','item_description','item_image','item_price','item_sale_price','item_discount','item_discount_start','item_discount_end','item_category','item_is','menu_order','item_display_in','item_for','show_at_home','is_delicous','is_you_may_like','item_status','is_non_discountAble','created_by','updated_by','created_at','updated_at'];
}

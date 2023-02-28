<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
	/**Table Name**/
	protected $table = 'stores';
    /**Primary Key**/
    protected $primaryKey = 'store_id';
	/**Fields**/
    protected $fillable = ['user_id','store_title','store_content','store_status','store_extra_charges','store_enable_tax','store_enable_sur_charge','store_enable_tip','store_tax','store_sur_charges','store_delivery_boy_tips','store_name','store_address','store_postalCode','store_city','store_suburb','store_country','store_pickup_minOrder','store_you_may_like_item_show_count','store_delivery_minOrder','store_food_type','store_location_phone','store_location_email','store_menu_style','media','sort_order','post_template','created_at','updated_at'];
}

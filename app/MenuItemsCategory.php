<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItemsCategory extends Model
{
	/**Table Name**/
	protected $table = 'menu_items_category';
    /**Primary Key**/
    protected $primaryKey = 'item_cat_id';
	/**Fields**/
    protected $fillable = ['store_id','cat_name','cat_slug','cat_description','cat_image','menu_order','cat_status','created_by','updated_by','created_at','updated_at'];
}

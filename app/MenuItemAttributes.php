<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItemAttributes extends Model
{
	/**Table Name**/
	protected $table = 'menu_item_attributes';
    /**Primary Key**/
    protected $primaryKey = 'item_attr_id';
	/**Fields**/
    protected $fillable = ['menu_item_id','user_id','menu_attr_id','attr_name','attr_desc','attr_price','attr_size','attr_status','attr_default_choice','created_at','updated_at'];
}

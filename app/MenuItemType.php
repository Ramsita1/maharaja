<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItemType extends Model
{
	/**Table Name**/
	protected $table = 'menu_item_type';
    /**Primary Key**/
    protected $primaryKey = 'item_type_id';
	/**Fields**/
    protected $fillable = ['store_id','type_name','type_description','created_by','updated_by','created_at','updated_at'];
}

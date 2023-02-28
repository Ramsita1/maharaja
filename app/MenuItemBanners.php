<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItemBanners extends Model
{
	/**Table Name**/
	protected $table = 'menu_item_banners';
    /**Primary Key**/
    protected $primaryKey = 'banner_id';
	/**Fields**/
    protected $fillable = ['store_id','banner_name','banner_image','created_at','updated_at'];
}

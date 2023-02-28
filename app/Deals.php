<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deals extends Model
{
	/**Table Name**/
	protected $table = 'store_deals';
    /**Primary Key**/
    protected $primaryKey = 'deal_id';
	/**Fields**/
    protected $fillable = ['store_id','deal_title','deal_description','deal_type','discount','min_order','max_discount','menu_item_id','category_id','start_date','end_date','start_time','end_time','week_of_day','location','buy_item','buy_item_qnty','get_item','get_item_qnty','is_deal_auto_apply','created_at','updated_at'];
}

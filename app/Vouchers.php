<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vouchers extends Model
{
	/**Table Name**/
	protected $table = 'store_vouchers';
    /**Primary Key**/
    protected $primaryKey = 'voucher_id';
	/**Fields**/
    protected $fillable = ['code','description','discount_type','store_id','discount','max_discount','min_order','usage_for','category_id','start_date','start_time','expiry_date','expiry_time','usage_many','usage_many_multiple','week_of_day','location','user_tags','free_delivery','created_at','updated_at'];
}

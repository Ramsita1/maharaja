<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreOnlineOrderTimings extends Model
{
	/**Table Name**/
	protected $table = 'stores_online_order_timings';
    /**Primary Key**/
    protected $primaryKey = 'store_online_order_timing_id';
	/**Fields**/
    protected $fillable = ['user_id','store_id','weekdays','comment','from_date','to_date','from_time','to_time','type','created_at','updated_at'];
}

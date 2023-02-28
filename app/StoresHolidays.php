<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoresHolidays extends Model
{
	/**Table Name**/
	protected $table = 'stores_holidays';
    /**Primary Key**/
    protected $primaryKey = 'store_holiday_id';
	/**Fields**/
    protected $fillable = ['store_id','date','full_day_off','close_start_time','close_end_time','status','created_at','updated_at'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    	/**Table Name**/
	protected $table = 'printers';
    /**Primary Key**/
    protected $primaryKey = 'id';
    //
    protected $fillable = ['printer_name','printer_ip_address','store_id','created_at','updated_at'];
}

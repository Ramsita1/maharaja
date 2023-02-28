<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
	/**Table Name**/
	protected $table = 'device_token';
    /**Primary Key**/
    protected $primaryKey = 'id';
	/**Fields**/
    protected $fillable = ['user_id','token','created_at','updated_at'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StorePickupLocations extends Model
{
	/**Table Name**/
	protected $table = 'store_pickup_locations';
    /**Primary Key**/
    protected $primaryKey = 'store_pickup_location_id';
	/**Fields**/
    protected $fillable = ['user_id','store_id','suburb','city','postal_code','created_at','updated_at'];
}

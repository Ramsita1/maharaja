<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoresSurgeCharges extends Model
{
	/**Table Name**/
	protected $table = 'stores_surge_charges';
    /**Primary Key**/
    protected $primaryKey = 'store_surge_id';
	/**Fields**/
    protected $fillable = ['store_id','date','reason','percentage','status','created_at','updated_at'];
}

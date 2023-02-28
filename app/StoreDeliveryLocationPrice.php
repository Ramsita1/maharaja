<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreDeliveryLocationPrice extends Model
{
	/**Table Name**/
	protected $table = 'stores_delivery_location_price';
    /**Primary Key**/
    protected $primaryKey = 'store_delivery_location_id';
	/**Fields**/
    protected $fillable = ['user_id','store_id','suburb','city','postal_code','store_delivery_partner_commission','store_delivery_partner_compensation','minimum_delivery_charge','minimum_delivery_order','charges','created_at','updated_at'];
}

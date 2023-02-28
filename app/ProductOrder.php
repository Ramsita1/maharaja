<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
   	/**Table Name**/
	protected $table = 'product_orders';
    /**Primary Key**/
    protected $primaryKey = 'order_id';
	/**Fields**/
    protected $fillable = ['user_id','store_id','name','email','phone','accpet_term_condition','attributes','product_detail','order_status','payment_status','payment_id','payment_getway','getway_raw','transaction_id','coupon','coupon_data','coupon_type','discount','sub_total','sur_charge','sub_total_with_surcharge','delivery_price','extra_charges','tip_price','total','grand_total','tax','billing_address','shipping_address','created_at','updated_at','send_to_kitchen','put_on_hold', 'driver_id'];
}

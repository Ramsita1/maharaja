<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuAttributeSize extends Model
{
	/**Table Name**/
	protected $table = 'menu_attribute_size';
    /**Primary Key**/
    protected $primaryKey = 'attribute_size_id';
	/**Fields**/
    protected $fillable = ['store_id','size_name','created_at','updated_at'];
}

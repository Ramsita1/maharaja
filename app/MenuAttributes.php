<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuAttributes extends Model
{
	/**Table Name**/
	protected $table = 'menu_attributes';
    /**Primary Key**/
    protected $primaryKey = 'menu_attr_id';
	/**Fields**/
    protected $fillable = ['store_id','attr_name','attr_status', 'attr_selection','attr_selection_mutli_value_min','attr_selection_mutli_value_max', 'attr_type', 'attr_main_choice','attr_mandatory','created_at','updated_at'];
}

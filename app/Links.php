<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
	/**Table Name**/
	protected $table = 'links';
    /**Primary Key**/
    protected $primaryKey = 'link_id';
	/**Fields**/
    protected $fillable = ['link_url','post_id','link_name','link_target','target_type','link_rel','link_order','link_parent','link_visible','created_at','updated_at'];
}

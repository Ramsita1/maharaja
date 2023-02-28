<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostMetas extends Model
{
	/**Table Name**/
	protected $table = 'post_metas';
    /**Primary Key**/
    protected $primaryKey = 'meta_id';
	/**Fields**/
    protected $fillable = ['post_id','meta_key','meta_value','created_at','updated_at'];
}

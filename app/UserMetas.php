<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMetas extends Model
{
	/**Table Name**/
	protected $table = 'user_metas';
    /**Primary Key**/
    protected $primaryKey = 'umeta_id';
	/**Fields**/
    protected $fillable = ['user_id','meta_key','meta_value','created_at','updated_at'];
}

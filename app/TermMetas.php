<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermMetas extends Model
{
	/**Table Name**/
	protected $table = 'term_metas';
    /**Primary Key**/
    protected $primaryKey = 'termmeta_id';
	/**Fields**/
    protected $fillable = ['term_id','meta_key','meta_value','created_at','updated_at'];
}

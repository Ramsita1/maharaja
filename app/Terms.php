<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{
	/**Table Name**/
	protected $table = 'terms';
    /**Primary Key**/
    protected $primaryKey = 'term_id';
	/**Fields**/
    protected $fillable = ['parent','name','slug','description','term_group','post_type','created_at','updated_at'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentMeta extends Model
{
    /**Table Name**/
	protected $table = 'comment_meta';
    /**Primary Key**/
    protected $primaryKey = 'comment_meta_id';
	/**Fields**/
    protected $fillable = ['comment_id','meta_key','meta_value','created_at','updated_at'];
}

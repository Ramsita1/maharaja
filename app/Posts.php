<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
	/**Table Name**/
	protected $table = 'posts';
    /**Primary Key**/
    protected $primaryKey = 'post_id';
	/**Fields**/
    protected $fillable = ['user_id','post_content','post_title','post_name','post_excerpt','post_status','comment_status','ping_status','post_parent','post_template','guid','media','menu_order','post_type','comment_count','created_at','updated_at'];
}

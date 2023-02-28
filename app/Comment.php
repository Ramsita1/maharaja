<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    /**Primary Key**/
    protected $primaryKey = 'comment_ID';
	/**Fields**/
    protected $fillable = ['post_id','comment_author','comment_author_url','comment_author_IP','comment_date','comment_date_gmt','comment_content','comment_karma','comment_approved','comment_agent','comment_type','comment_parent','user_id','created_at','updated_at'];
}

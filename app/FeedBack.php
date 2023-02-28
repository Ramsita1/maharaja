<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    protected $table = 'feed_backs';
    /**Primary Key**/
    protected $primaryKey = 'feedback_id';
	/**Fields**/
    protected $fillable = ['name','email','mobile','message','type','created_at','updated_at'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermRelations extends Model
{
	/**Table Name**/
	protected $table = 'term_relationships';
    /**Primary Key**/
    protected $primaryKey = 'relation_id';
	/**Fields**/
    protected $fillable = ['term_id','object_id','created_at','updated_at'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    /**Table Name**/
	protected $table = 'options';
    /**Primary Key**/
    protected $primaryKey = 'option_id';
}

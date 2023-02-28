<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempCartTable extends Model
{
    //
    protected $table = 'temp_cart_tables';
    /**Primary Key**/
    protected $primaryKey = 'cart_id';
}

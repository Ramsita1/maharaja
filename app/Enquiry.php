<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $primaryKey = 'Enquiry_id';

    protected $fillable = [
        'enquirer_name', 'enquirer_email', 'post_id','enquirer_phone','enquirer_message','created_at','updated_at'
    ];
}

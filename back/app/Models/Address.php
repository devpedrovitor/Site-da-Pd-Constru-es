<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['street', 'number', 'neighborhood', 'city', 'state', 'zip_code', 'user_id'];
}

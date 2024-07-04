<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['name','alamat','email','telepon'];

    protected $hidden = ['created_at','updated_at'];
}

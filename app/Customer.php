<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'alamat', 'email', 'telepon'];

    protected $hidden = ['created_at', 'updated_at'];
}

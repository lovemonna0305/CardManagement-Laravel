<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_Masuk extends Model
{
    protected $table = 'Product_Masuk';

    protected $fillable = ['card_id','supplier_id','qty','tanggal'];

    protected $hidden = ['created_at','updated_at'];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

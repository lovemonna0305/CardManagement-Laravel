<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_Masuk extends Model
{
    protected $table = 'Product_Masuk';

    protected $fillable = ['card_id','Analytics_id','qty','tanggal'];

    protected $hidden = ['created_at','updated_at'];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function Analytics()
    {
        return $this->belongsTo(Analytics::class);
    }
}

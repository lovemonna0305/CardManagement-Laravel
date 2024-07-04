<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card_Register extends Model
{
    protected $table = 'card_registers';

    protected $fillable = ['card_id','customer_id','qty','tanggal'];

    protected $hidden = ['created_at','updated_at'];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

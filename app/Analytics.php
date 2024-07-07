<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Analytics extends Model {

	protected $table = 'analytics';

	protected $fillable = ['card_id', 'non_working_days', 'non_working_hours', 'non_bus_lines','file_url'];

	protected $hidden = ['created_at', 'updated_at'];

	public function card()
    {
        return $this->belongsTo(Card::class);
    }
}

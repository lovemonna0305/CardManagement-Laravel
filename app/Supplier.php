<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {
	protected $fillable = ['card_id', 'non_working_days', 'non_working_hours', 'noe_bus_lines','file_url'];

	protected $hidden = ['created_at', 'updated_at'];
}

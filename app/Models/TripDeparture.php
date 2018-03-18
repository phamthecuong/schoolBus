<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripDeparture extends Model
{
	public $timestamps = false;
	
    public function departure()
    {
    	return $this->belongsTo('App\Models\Departure', 'departure_id', 'id');
    }

}

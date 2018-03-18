<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;

class TripLocation extends Model
{
	use Uuid32ModelTrait;
    protected $fillable = [
        'trip_id', 'lat', 'long'
    ];
}

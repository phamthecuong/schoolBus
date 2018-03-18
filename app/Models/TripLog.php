<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripLog extends Model
{
    public function info()
    {
        return $this->belongsTo('App\Models\User', 'student_id', 'id');
    }

    public function information()
    {
        return $this->belongsTo('App\Models\Trip', 'trip_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTrip extends Model
{
	protected $table = 'student_trips';

	public $timestamps = false;
	
	public function info()
    {
        return $this->belongsTo('App\Models\User', 'student_id', 'id');
    }
}

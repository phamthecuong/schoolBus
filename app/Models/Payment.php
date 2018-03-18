<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	protected $table = 'payments';
    protected $appends = ['student_name'];
    public function students()
    {
    	return $this->belongsTo('App\Models\Student', 'student_id');
    }

    //scope
    public function scopePaymentInfo($query)
    {
        return $query->with('students.users');
    }

    public function getStudentNameAttribute()
    {   
        return $this->students->full_name;
    }

}

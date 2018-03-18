<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
 	protected $table = 'teachers';

    public $appends = ['class_name'];

    //public $timestamps = false;
 	function users()
    {
        return $this->morphMany('App\Models\User', 'profile');
    }

    public function avatar()
    {
        return $this->belongsTo('App\Models\Media', 'avatar_id');
    }

    public function getClassNameAttribute()
    {
        $class;
        $teacher = Teacher::where('id', $this->id)->first();
        $class = @Classes::where('teacher_id', $teacher->id)->first()->name;
        return @$class;
    }

}
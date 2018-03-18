<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Parents extends Model
{
    protected $table = 'parents';

    public $appends = ['child_name', 'bus_name', 'user_email'];

    public $timestamps = true;

    // public function departure()
    // {
    //     return $this->belongsTo('App\Models\Departure', 'departure_id', 'id');
    // }

    public function avatar()
    {
        return $this->belongsTo('App\Models\Media','avatar_id');
    }

    function users()
    {
    	return $this->morphMany('App\Models\User', 'profile');
    }

    public function students()
    {
        return $this->belongsToMany('App\Models\Student', 'student_parents', 'parent_id', 'student_id');
    }

    public function distances()
    {
        return $this->belongsToMany('App\Models\Distance', 'parent_distances', 'parent_id', 'distance_id')->orderBy('about');
    }

    public function getChildNameAttribute()
    {
        return implode(', ', $this->students()->groupBy('full_name')->pluck('full_name')->all());
    }

    public function getBusNameAttribute()
    {
        $students = $this->students()->groupBy('students.id')->pluck('students.id')->all();
        
        $result = \DB::table('bus')
            ->select('bus.name')
            ->join('trips', 'trips.bus_id', '=', 'bus.id')
            ->join('student_trips', 'student_trips.trip_id', '=', 'trips.id')
            ->whereIn('student_trips.student_id', $students)
            ->groupBy('bus.id')
            ->get()
            ->pluck('name')
            ->toArray();

        return implode(', ', $result);
    }

    public function getUserEmailAttribute()
    {
        $parent = @$this->where('id', $this->id)->first();
        return @$parent->users->first()->email;
    }
}

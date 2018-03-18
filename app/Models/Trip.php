<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use Auth;

class Trip extends Model
{
    // public $appends = ["school"];
    public $appends = ['driver_name'];

    public function getDriverAttribute()
    {
        return @$this->drivers[0]->full_name;

    }

    public function students()
    {
        return $this->belongsToMany('App\Models\Student', 'student_trips', 'trip_id', 'student_id')->withPivot('status', 'pick_up_id', 'drop_off_id', 'updated_at')->withTimestamps();
    }

    public function student_bridges()
    {
        return $this->hasMany('App\Models\StudentTrip', 'trip_id');
    }

    // public function getChildrenAttribute()
    // {
    //     if (Auth::user()->profile_type == 3)
    //     {
    //         return Student::whereIn("id", Auth::user()->profile->students->pluck("id"))->with('users')->get();
    //     }
    //     else
    //     {
    //         return null;
    //     }
    // }
    

    public function departures()
    {
        return $this->belongsToMany('App\Models\Departure', 'trip_departures', 'trip_id', 'departure_id')->withPivot('arrive_time', 'finish_at', 'here')->orderBy('arrive_time')->withTimestamps();
    }

    public function drivers()
    {
        return $this->belongsToMany('App\Models\Driver', 'driver_trips', 'trip_id', 'driver_id');
    }

    public function types()
    {
        return $this->belongsTo('App\Models\TripType', 'type', 'id');
    }

    public function buses()
    {
        return $this->belongsTo('App\Models\Bus', 'bus_id', 'id');
    }

    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    public function locations()
    {
        return $this->hasMany('App\Models\TripLocation', 'trip_id')->orderBy('created_at', "DESC");
    }
    //Scope
    public function scopeInfoByDate($query, $date, $parent_id)
    {
        return $query->with(['types', 'buses', 'school', 'drivers.users', 'departures', 'students.classes.school', 'students.departure', 'students.parents' => function ($q) use ($parent_id) {
            $q->where('parent_id', $parent_id);
        }])->whereDate('arrive_date', $date)->orderBy('arrive_date', 'desc');
    }

    public function getDriverNameAttribute()
    {
        // $trips = @$this->with('drivers.users')->first();
        return $this->drivers()->first()->full_name;
    }

    public function scopeDetailForParents($query, $parent_id)
    {
        return $query->with(['types', 'buses', 'school', 'drivers.users', 'departures', 'students.classes.school', 'students.departure', 'students.parents' => function ($q) use ($parent_id) {
            $q->where('parent_id', $parent_id);
        }])->withCount('departures')->withCount('students');
    }

    public function scopeOfParent($query, $parent_id)
    {
        return $query->whereHas("students", function ($query) use ($parent_id) {
            $query->whereHas('parents', function($query) use ($parent_id) {
                $query->where('parent_id', $parent_id);
            });
        });
    }

    public function scopeInfoTripForDriver($query, $date)
    {
        return $query->with('types', 'buses', 'school', 'drivers.users', 'departures', 'students')
            ->withCount('departures')
            ->withCount('students')
            ->where('arrive_date', $date)
            ->orderBy('started_at');
    }

    public function scopeDetail($query)
    {
        return $query->with('types', 'buses', 'school', 'drivers.users', 'departures', 'students')
            ->withCount('departures')
            ->withCount('students')
            ->orderBy('arrive_date', 'desc');
    }

    public function scopeOfDriver($query, $driver_id)
    {
        return $query->whereHas('drivers', function ($q) use ($driver_id) {
            $q->where('id', $driver_id);
        });
    }

}

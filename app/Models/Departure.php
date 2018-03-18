<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\User;

class Departure extends Model
{

    protected $table = 'departures';

    public function trips()
    {
        return $this->belongsToMany('App\Models\Trip', 'trip_departures', 'departure_id', 'trip_id')->withPivot('arrive_time','finish_at');
    }

    public function pickUpStudents()
    {
        return $this->belongsToMany('App\Models\Student', 'student_trips', 'pick_up_id', 'student_id')->withPivot('status');
    }

    public function dropOffStudents()
    {
        return $this->belongsToMany('App\Models\Student', 'student_trips', 'drop_off_id', 'student_id')->withPivot('status');
    }

    public function students()
    {
        return $this->hasMany('App\Models\Student');
    }

    static public function listDeparturce()
    {
    	$school_id = User::find(Auth::user()->id)->profile->school_id;
    	$dp = Departure::where('school_id', $school_id)->get();
        $dp_name[] = ['name' => trans('title.Choose_departure'), 'value' => '#'];
    	foreach ($dp as $r)
    	{
    		$dp_name[] = ['name' => $r->name, 'value' => $r->id];
    	}
    	return $dp_name;
    }

    static public function allToOption()
    {
    	$departure = Departure::get();
        $array = [];
    	foreach ($departure as $row)
    	{
    		$array[] = ['name' => $row->name,
                'value' => $row->id];
    	}
    	return $array;
    }
    static public function listAllDepartureBySchool($trip_id)
    {
        $school_id = User::find(Auth::user()->id)->profile->school_id;
        $departure = Departure::with('trips')->whereHas('trips', function ($query) use ($trip_id){
            $query->where('trips.id',$trip_id);
        })->where('school_id',$school_id)->get();
        $array = [];
        foreach ($departure as $row)
        {
            $array[] = ['name' => $row->name, 'value' => $row->id];
        }
        return $array;
    }
    
    static public function listDepartureBySchool($trip_id)
    {
        $school_id = User::find(Auth::user()->id)->profile->school_id;
        $departure = Departure::whereDoesntHave('trips', function ($query) use ($trip_id){
            $query->where('trips.id',$trip_id);
        })->where('school_id',$school_id)->get();
        $array = [];
        foreach ($departure as $row)
        {
            $array[] = ['name' => $row->name, 'value' => $row->id];
        }
        return $array;
    }
}

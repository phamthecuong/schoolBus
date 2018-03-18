<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
class Driver extends Model
{
    protected $table = 'drivers';

    public $appends = ['bus_name', 'bus_number', 'user_email'];

    public function trips()
    {
        return $this->belongsToMany('App\Models\Trip', 'driver_trips', 'driver_id', 'trip_id');
    }

    public function avatar()
    {
        return $this->belongsTo('App\Models\Media','avatar_id');
    }

    function users()
    {
        return $this->morphMany('App\Models\User', 'profile');
    }

    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    static function allToOption($has_all = FALSE)
    {
        $data = User::where('profile_type', 4)->get();
        $dataset = array();
        if ($has_all !== FALSE) {
            $dataset += array(
                -1 => $has_all,
            );
        }
        foreach ($data as $r) {
            $dataset[$r->id] = array(
                'name' => $r->name,
                'value' => $r->profile_id,
                'selected' => FALSE,
            );
        }
        return $dataset;
    }

    public function getBusNameAttribute()
    {
        $result = \DB::table('bus')
            ->select('bus.name')
            ->join('trips', 'trips.bus_id', '=', 'bus.id')
            ->join('driver_trips', 'driver_trips.trip_id', '=', 'trips.id')
            ->where('driver_trips.driver_id', $this->id)
            ->groupBy('bus.id')
            ->get()
            ->pluck('name')
            ->toArray();

        return implode(', ', $result);
        // $name;
        // $driver = $this->where('id', $this->id)->has('users')->with('users')
        //     ->with(['trips' => function($query) {
        //         $query->orderBy('arrive_date', 'desc')->limit(1);
        //     }])->first();
        // $trip = @$driver->trips->first()->bus_id;
        // $name = @Bus::find($trip)->name;
        // return @$name;
    }

    public function getBusNumberAttribute()
    {
        $result = \DB::table('bus')
            ->select('bus.bus_number')
            ->join('trips', 'trips.bus_id', '=', 'bus.id')
            ->join('driver_trips', 'driver_trips.trip_id', '=', 'trips.id')
            ->where('driver_trips.driver_id', $this->id)
            ->groupBy('bus.id')
            ->get()
            ->pluck('bus_number')
            ->toArray();

        return implode(', ', $result);
        // $number;
        // $driver = $this->where('id', $this->id)->has('users')->with('users')
        //     ->with(['trips' => function($query) {
        //         $query->orderBy('arrive_date', 'desc')->limit(1);
        //     }])->first();
        // $trip = @$driver->trips->first()->bus_id;
        // $number = @Bus::find($trip)->bus_number;
        // return @$number;
    }

    public function getUserEmailAttribute()
    {
        $parent = @$this->where('id', $this->id)->first();
        return @$parent->users->first()->email;
    }

    static function getDriverBySchool()
    {
        $school_id = Auth::user()->profile->school_id;
        $driver = Driver::where('school_id', $school_id)->get();
        foreach ($driver as $r)
        {
            $data[] = ['name' => $r->full_name, 'value' => $r->id];
        }
        return $data;
    }
}

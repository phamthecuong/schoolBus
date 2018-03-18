<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
class Bus extends Model
{
    protected $table = 'bus';

    public $appends = ['supervisor_name', 'supervisor_phone'];

    static function allToOption($has_all = FALSE)
    {
        $data = Bus::get();
        $dataset = array();
        if ($has_all !== FALSE)
        {
            $dataset+= array(
                -1 => $has_all,
            );
        }
        foreach ($data as $r)
        {
            $dataset[$r->id] = array(
                'name' => $r->name,
                'value' => $r->id,
                'selected' => FALSE,
            );
        }
        return $dataset;
    }

    public function trips()
    {
        return $this->hasMany('App\Models\Trip', 'bus_id');
    }

    public function getSupervisorNameAttribute()
    {
        $bus = $this->where('id', $this->id)->first();
        $trip_id = @Trip::where('bus_id', $bus->id)->first()->id;
        $driver = @Driver::whereHas('trips', function ($query) use($trip_id){
            $query->where('trip_id', $trip_id);
        })->first()->full_name;
        return @$driver;
    }

    public function getSupervisorPhoneAttribute()
    {
        $driver_phone;
        $bus = $this->where('id', $this->id)->first();
        $trip_id = @Trip::where('bus_id', $bus->id)->first()->id;
        $driver_phone = @Driver::whereHas('trips', function ($query) use($trip_id){
            $query->where('trip_id', $trip_id);
        })->first()->phone_number;
        return $driver_phone;
    }

    static function getBusBySchool()
    {
        $school_id = Auth::user()->profile->school_id;
        $bus = Bus::where('school_id', $school_id)->get();
        foreach ($bus as $r)
        {
            $data[] = ['name' => $r->name,'value' => $r->id];
        }
        return $data;
    }
}

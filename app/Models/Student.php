<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    public $appends = ['class_name', 'bus_name'];

    public function classes()
    {
        return $this->belongsToMany('App\Models\Classes','class_students', 'student_id', 'class_id');
    }

    public function payments() 
    {
        return $this->hasMany('App\Models\Payment');
    }
    public function trips()
    {
        return $this->belongsToMany('App\Models\Trip', 'student_trips', 'student_id', 'trip_id')->withPivot('status', 'id');
    }

    public function tripLogs()
    {
        return $this->belongsToMany('App\Models\Trip', 'trip_logs', 'student_id', 'trip_id')->withPivot('status', 'updated_at');
    }

    public function parents()
    {
        return $this->belongsToMany('App\Models\Parents', 'student_parents', 'student_id', 'parent_id');
    }

    public function departure()
    {
        return $this->belongsTo('App\Models\Departure', 'departure_id', 'id');
    }

    public function departure2()
    {
        return $this->belongsTo('App\Models\Departure', 'departure_id2', 'id');
    }

    function users()
    {
    	return $this->morphMany('App\Models\User', 'profile');
    }

    //scope

    public function scopeLogs($query)
    {
        return $query->with('users', 'tripLogs.buses', 'tripLogs.types', 'tripLogs.drivers.users', 'tripLogs.departures');
    }

    public function scopeSchoolFilter($query, $school_id)
    {
        return $query->whereHas('classes', function($query) use ($school_id) {
                $query->where('school_id', $school_id);
            });
    }

    public function getClassNameAttribute()
    {
        return \DB::table('classes')
            ->select('classes.name')
            ->join('class_students', 'class_students.class_id', '=', 'classes.id')
            ->join('students', 'class_students.student_id', '=', 'students.id')
            ->where('students.code', $this->code)
            ->orderBy('classes.created_at', 'desc')
            ->first()
            ->name;
    }

    public function getBusNameAttribute()
    {
        // $bus;
        // $student = Student::where('id', $this->id)->first();
        // $bus_id = @$student->trips[0]->bus_id;
        // $bus = @Bus::where('id', $bus_id)->first()->name;
        // return @$bus;
        $result = \DB::table('bus')
            ->select('bus.name')
            ->join('trips', 'trips.bus_id', '=', 'bus.id')
            ->join('student_trips', 'student_trips.trip_id', '=', 'trips.id')
            ->join('students', 'student_trips.student_id', '=', 'students.id')
            ->where('students.code', $this->code)
            ->groupBy('bus.id')
            ->get()
            ->pluck('name')
            ->toArray();

        return implode(', ', $result);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\User;
use Auth;
class Classes extends Model
{
    protected $table = 'classes';

    public function student()
    {
        return $this->belongsToMany('App\Models\Student','class_students', 'class_id','student_id');
    }

    public function school()
    {
        return $this->belongsTo('App\Models\School');
    }

    static public function allToOption($flash = NULL)
    {
        $school_id = Auth::user()->profile->school_id;
    	$class = Classes::where('school_id', $school_id)->get();
        $array = [];
        
    	foreach ($class as $row)
    	{
    		$array[] = [
                    'name' => $row->name,
                    'value' => $row->id
                ];
    	}
    	return $array;
    }

    static public function allToOptionTrip($flash = NULL)
    {
        $school_id = Auth::user()->profile->school_id;
        $class = Classes::has('student')->where('school_id', $school_id)->get();
        $array = [];
        if ($flash != NULL)
        {
            $array[] = ['name' => trans("title.please_chose_class"), 'value' => '#'];
        }
        foreach ($class as $row)
        {
            $array[] = [
                    'name' => $row->name,
                    'value' => $row->id
                ];
                
        }
        return $array;

    }

    static public function getListStudent($class_id)
    {
        //$student = Student::join('users','students.id','=','profile_id')->where('class_id',$class_id)->where('profile_type',2)->get(); 
        $student = Student::join('class_students','students.id','=','student_id')->where('class_id',$class_id)->get();
        foreach ($student as $row)
        {
            $student[] = ['name' => $row->full_name, 'value' => $row->student_id];
        }
        return $student;
    }

    static public function allToOptionYear()
    {
        $school_id = Auth::user()->profile->school_id;
        $class = Classes::where('school_id', $school_id)->get();
        $data = [];
        $data[] = ['name' => trans('validate.choose'), 'value' => ''];
        foreach ($class as $row)
        {
            $data[] = [
                'name' => $row->year,
                'value' => $row->year
            ];
        }
        return $data;
    }
}

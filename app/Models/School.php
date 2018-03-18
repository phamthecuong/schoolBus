<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\School;
use App\Models\User;
use App\Models\Classes;
use App\Models\Teacher;
use App\Models\Parents;
use Auth;
class School extends Model
{	
	protected $table = 'schools';
   	protected $fillable = ['name'];

    public function departures()
    {
        return $this->hasMany('App\Models\Departure', 'school_id', 'id');
    }
    public function classes()
    {
        return $this->hasMany('App\Models\Classes', 'school_id', 'id');
    }
    static public function allToOption()
    {
    	$school = @School::get();
    	foreach ($school as $row)
    	{
    		$arr[] = ['name' => $row->name, 'value' => $row->id];
    	}
    	return $arr;
    }

    static public function getListTeacher()
    {
        $school_id = Auth::user()->profile->school_id;
        $teacher = Teacher::where('school_id', $school_id)->get();
        $name_teacher[] = ['name' => trans('class.name_teacher') ,'value' => ''];
        foreach ($teacher as $row)
        {
            // $user = User::where('profile_id', $row->id)->first();
            $name_teacher[] = ['name' => $row->full_name, 'value' => $row->id];
        }
        return $name_teacher;
    }

    static public function getListParent()
    {
        $school_id = Auth::user()->profile->school_id;
        $parents = Parents::whereHas('students.classes', function($query) use ($school_id) {
                $query->where('school_id', $school_id);
            })->get();
        $data = [];
        $data[] = ['name' => trans('title.Choose_parent'), 'value' => '#'];
        foreach ($parents as $p)
        {
            $data[] = ['name' => $p->full_name, 'value' => $p->id];
        }
        return $data;
    }

    static public function getListClass()
    {
        $user_id = Auth::user()->profile_id;
        $school_admin = SchoolAdmin::findOrFail($user_id);
        $id = $school_admin->school_id;
        $class = Classes::where('school_id', $id)->get();
        $class_list[] = ['name'=>'Choose a class','value'=>''];
        foreach ($class as $row)
        {
            $class_list[] = ['name'=> $row->name, 'value' => $row->id];
        }
        return $class_list;
    }

    static public function getListSchoolByParent()
    {
        $parents = Parents::with('students.classes')->findOrFail(Auth::user()->profile_id);
        foreach ($parents->students as $student)
        {
            $class_id[] = $student->classes[0]->id;
        }
        $schools = School::with('classes')->whereHas('classes', function ($query) use ($class_id) {
            $query->whereIn('classes.id', $class_id);
        })->get();
        $data[] = ['name' => 'Hãy chọn trường', 'value' => ''];
        foreach ($schools as $school)
        {
            $data[] = ['name' => $school->name, 'value' => $school->id];
        }
        return $data;
    }
    
}

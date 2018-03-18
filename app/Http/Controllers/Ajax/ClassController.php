<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Classes;
use App\Models\School;
use App\Models\Teacher;
use Yajra\Datatables\Facades\Datatables;
use Auth;
class ClassController extends Controller
{
    public function index()
    {   
        $school_id = Auth::user()->profile->school_id;
    	$class = Classes::where('school_id', $school_id)->get();
        foreach ($class as $k => $r)
        {
            $class[$k]['teacher'] = @Teacher::find($r->teacher_id)->full_name;
        }
        $class = collect($class);
    	return Datatables::of($class)			
	        ->addColumn('action', function($u){
                $action = [];
                $action[] = \Form::lbButton(
                    "school/class/{$u->id}/edit", 
                    'get', 
                    trans('general.edit'),
                    ["class" => "btn btn-xs btn-info"]
                )->toHtml();

                $student = Classes::find($u->id)->student;
                if (count($student) >0)
                {
                    $onclick = "return confirm('".trans('class.Class_has_a_data_student,you_must_clear')."')";
                }
                else
                {
                    $onclick = "return confirm('".trans('class.Are_you_sure?')."')";
                }

                $action[] = \Form::lbButton(
                    route('class.destroy', [$u->id]),
                    'delete',
                    trans('general.delete'),
                    [
                        "class" => "btn btn-xs btn-danger",
                        "onclick" => $onclick,
                    ]
                )->toHtml();
                return implode(' ', $action); 
	        })
	        ->make(true);

    }
}

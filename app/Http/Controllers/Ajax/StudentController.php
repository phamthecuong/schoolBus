<?php
namespace App\Http\Controllers\Ajax;

use App\Models\Bus;
use App\Models\ClassStudent;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Classes;
use App\Models\School;
use App\Models\Departure;
use App\Models\StudentTrip;
use Yajra\Datatables\Facades\Datatables;
use Auth;
class StudentController extends Controller
{
    public function index()
    {   
        $school_id = \Auth::user()->profile->school_id;
    	$student = Student::select('students.id', 'students.full_name', 'students.gender',
            'students.address', 'students.departure_id', 'students.departure_id2', 'students.code', 'students.created_at')
        ->with('departure', 'departure2')
        ->whereHas('classes', function($query) use($school_id) {
            $query->where('school_id', $school_id);
        });
        // ->with(['departure', 'departure2']);
        // ->get();
        // foreach ($student as $k => $r)
        // {   
        //     $student[$k]['dp_name'] = @Departure::find($r->departure_id)->name;
        //     $student[$k]['dp_name2'] = @Departure::find($r->departure_id2)->name;
        //     if ($r->gender == 1)
        //     {
        //         $student[$k]['sex'] = trans('title.male');
        //     }
        //     else
        //     {
        //         $student[$k]['sex'] = trans('title.female');
        //     }
        // }
        // $student = collect($student);
        return Datatables::eloquent($student)
	        ->addColumn('action', function($u) {

                $action = [];
                $action[] = \Form::lbButton(
                    "school/student/{$u->id}/edit", 
                    'get',
                    trans('general.edit'),
                    ["class" => "btn btn-xs btn-info"]
                )->toHtml();

                $trip = StudentTrip::where('student_id', $u->id)->get();
                if (count($trip) > 0)
                {
                    $onclick = 'alert("'.trans("student.have_data_in_trip_you_have_to_delete_data_in_trip").'")';
                   
                }
                else
                {
                    $onclick = 'confirm("'.trans("student.are_you_sure?").'")';
                }
                $action[] = \Form::lbButton(
                     route('student.destroy', [$u->id]),
                    'delete',
                    trans('general.delete'),
                    [
                        "class" => "btn btn-xs btn-danger",
                        "onclick" => "return ".$onclick
                    ]
                )->toHtml();
                return implode(' ', $action); 
	        })
            ->addColumn('sex', function($r) {
                if ($r->gender == 1)
                {
                    return trans('title.male');
                }
                else
                {
                    return trans('title.female');
                }
            })
	        ->make(true);
    }
}

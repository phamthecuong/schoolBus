<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Departure;
use App\Models\User;
use App\Models\Classes;
use App\Models\Teacher;
use Yajra\Datatables\Facades\Datatables;
use Auth;
class TeacherController extends Controller
{
    public function index()
    {
        $school_id = User::find(Auth::user()->id)->profile->school_id;
        $teacher = Teacher::where('school_id' , $school_id)->get();
        foreach ($teacher as $k => $r)
        {   
            $user =  @User::where('profile_type', 'teacher')->where('profile_id', $r->id)->first();
            $teacher[$k]['email'] = @$user->email;
            
        }
    	$teacher_info = collect($teacher);
    	return Datatables::of($teacher_info)			
	        ->addColumn('action', function($p){
                $action = [];
                $action[] = \Form::lbButton(
                    "/school/teacher/{$p->id}/edit", 
                    'get', 
                    trans('general.edit'),
                    ["class" => "btn btn-xs btn-info"]
                )->toHtml();
                $class = Classes::where('teacher_id', $p->id)->get();
                if (count($class) > 0)
                {
                    $onclick = "alert('Giáo viên này đang làm chủ nhiệm 1 lớp nên không thể xoá.'); return false;";
                }
                else
                {
                    $onclick = "return confirm('Bạn có chắc chắn không?')";
                }
                $action[] = \Form::lbButton(
                     route('teacher.destroy', [$p->id]),
                    'delete',
                    trans('general.delete'),
                    [
                        "class" => "btn btn-xs btn-danger",
                        "onclick" =>  $onclick
                    ]
                )->toHtml();
                return implode(' ', $action);   
	        })
            ->addColumn('class', function ($p){
            })
	        ->make(true);
    }
}

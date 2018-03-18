<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Bus;
use App\Models\Student;
use App\Models\StudentTrip;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Departure;
use App\Models\User;
use App\Models\Parents;
use App\Models\StudentParent;
use Yajra\Datatables\Facades\Datatables;
use Auth;
class ParentController extends Controller
{
    public function index()
    {
        $school_id = User::find(Auth::user()->id)->profile->school_id;
        $parent = Parents::has('users')
            ->whereHas('students.classes', function($query) use ($school_id) {
                $query->where('school_id', $school_id);
            });
        return Datatables::of($parent)
            ->addColumn('action', function($p){
                $action = [];
                $action[] = \Form::lbButton(
                    "/school/parent/{$p->id}/edit", 
                    'get', 
                    trans('general.edit'),
                    ["class" => "btn btn-xs btn-info"]
                )->toHtml();
                $action[] = '<a class= "btn btn-xs btn-warning" href = /school/parent/'.$p->id.'/password>'.  trans('parent.change_password') .'</a>';
                $student = StudentParent::where('parent_id', $p->id)->get();
                if ($student)
                {
                    $onclick = "return confirm(' have data Are you sure?')"; 
                }
                else
                {
                    $onclick = "return confirm(' Are you sure?')";
                }
               /* $action[] = \Form::lbButton(
                     route('parent.destroy', [@$p->id]),
                    'delete',
                    trans('general.delete'),
                    [
                        "class" => "btn btn-xs btn-danger",
                        "onclick" => $onclick
                    ]
                )->toHtml();*/
                
                
                return implode(' ', $action);   
	        })
	        ->make(true);
    }

    public function getParentPayment()
    {
        
    }
}

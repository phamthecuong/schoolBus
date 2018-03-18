<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\School;
use App\Models\SchoolAdmin;
use Yajra\Datatables\Facades\Datatables;
class SchoolController extends Controller
{
	public function index()
	{
	    $school = School::get();
    	return Datatables::of($school)
	        ->addColumn('action', function($s){
	        	$action = [];
                $action[] = \Form::lbButton(
                    "admin/school/{$s->id}/edit", 
                    'get', 
                    trans('general.edit'),
                    ["class" => "btn btn-xs btn-info"]
                )->toHtml();
                $action[] = \Form::lbButton(
                    "admin/school/{$s->id}/admin", 
                    'get',
                    trans('general.admin_info'),
                    ["class" => "btn btn-xs btn-warning"]
                )->toHtml();
                $chk = Classes::where('school_id', $s->id)->count() + SchoolAdmin::where('school_id', $s->id)->count();

                if ($chk > 0)
                {
                    $onclick = "alert('".trans('school.school_has_class_you_have_to_clear')."'); return false";
                }
                else
                {
                    $onclick = "return confirm('".trans('school.Are_you_sure?')."')";
                } 
                $action[] = \Form::lbButton(
                     route('school.destroy', [$s->id]),
                    'delete',
                    trans('general.delete'),
                    [
                        "class" => "btn btn-xs btn-danger",
                        "onclick" => $onclick
                    ]
                )->toHtml();
              
                return implode(' ', $action);	
	        })
	        ->make(true);


	}
}

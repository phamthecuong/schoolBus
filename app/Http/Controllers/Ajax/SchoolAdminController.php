<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SchoolAdmin;
use App\Models\School;
use App\Models\User;
use Yajra\Datatables\Facades\Datatables;
class SchoolAdminController extends Controller
{
    public function index($school_id)
    {
    	$admin = SchoolAdmin::where('school_id', $school_id)->get();
        foreach ($admin as $k => $r)
        {
            $user = @User::where('profile_id', $r->id)->where('profile_type', 'school')->first();
            $admin[$k]['email'] = @$user->email;
        }
        $admin = collect($admin);
        // echo "<pre>";
        // print_r($admin->toArray());
        // echo "</pre>";
    	return Datatables::of($admin)			
            ->addColumn('action', function($a) use($school_id){
                $action = [];
                $action[] = \Form::lbButton(
                    "/admin/school/{$school_id}/admin/{$a->id}/edit", 
                    'get', 
                    trans('general.edit'),
                    ["class" => "btn btn-xs btn-info"]
                )->toHtml();

                $action[] = \Form::lbButton(
                    route('school.admin.destroy', [$school_id, $a->id]),
                    'delete',
                    trans('general.delete'),
                    [
                        "class" => "btn btn-xs btn-danger",
                        "onclick" => "return confirm('".trans('school_admin.Are_you_sure?')."')"
                    ]
                )->toHtml();

                $action[] = \Form::lbButton(
                    '/admin/school/'. $school_id .'/admin/'. $a->id. '/change_password',
                    'get',
                    trans('school_admin.change_password'),
                    [
                        "class" => "btn btn-xs btn-warning",
                    ]
                )->toHtml();
                return implode(' ', $action);	
	        })
	        ->make(true);
    }
}

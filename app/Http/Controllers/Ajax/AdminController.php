<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Classes;
use App\Models\School;
use App\Models\SchoolAdmin;
use Yajra\Datatables\Facades\Datatables;
use Auth;
class AdminController extends Controller
{
    public function index()
    {   
        $school_id = \Auth::user()->profile->school_id;
        $admin = SchoolAdmin::where('school_id', $school_id)->get();
        foreach ($admin as $k => $r)
        {
            $user = User::where('profile_id', $r->id)->where('profile_type', 'school')->first();
            $admin[$k]['email'] = @$user->email;

        }
        return Datatables::of($admin)            
            ->addColumn('action', function($u){
                $action = [];
                $action[] = \Form::lbButton(
                    "admin/acount/{$u->id}/edit", 
                    'get',
                    trans('general.edit'),
                    ["class" => "btn btn-xs btn-info"]
                )->toHtml();

                $action[] = \Form::lbButton(
                     url('admin/acount/' . $u->id),
                    'delete',
                    trans('general.delete'),
                    [
                        "class" => "btn btn-xs btn-danger",
                        "onclick" => "return confirm('".trans("admin.Are_you_sure?")."')"
                    ]
                )->toHtml();
                $action[] = \Form::lbButton(
                     url('admin/acount/change_password/' . $u->id),
                    'get',
                    trans('general.change_password'),
                    [
                        "class" => "btn btn-xs btn-warning",
                    ]
                )->toHtml();
                return implode(' ', $action); 
            })
            ->make(true);

    }
}

<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Classes;
use App\Models\School;
use Yajra\Datatables\Facades\Datatables;
class AcountController extends Controller
{
    public function index()
    {   
    	$user = User::where('profile_type', 'admin')->where('profile_id', NULL)->get();
    	return Datatables::of($user)			
	        ->addColumn('action', function($u){
                $action = [];
                $action[] = \Form::lbButton(
                    "admin/account/{$u->id}/edit", 
                    'get',
                    trans('general.edit'),
                    ["class" => "btn btn-xs btn-info"]
                )->toHtml();

                $action[] = \Form::lbButton(
                     url('admin/account/' . $u->id),
                    'delete',
                    trans('general.delete'),
                    [
                        "class" => "btn btn-xs btn-danger",
                        "onclick" => "return confirm('".trans("account.Are_you_sure?")."')"
                    ]
                )->toHtml();
                $action[] = \Form::lbButton(
                     url('admin/account/change_password/' . $u->id),
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

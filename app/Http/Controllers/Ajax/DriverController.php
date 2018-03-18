<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Bus;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use Auth;
use Yajra\Datatables\Facades\Datatables;
class DriverController extends Controller
{
    public function index()
    {   
        $school_id = Auth::user()->profile->school_id;
        $driver = Driver::where('school_id' , $school_id)->with('users')->has('users')->withCount('trips')->get();
            // ->with(['trips' => function($query) {
            //     $query->orderBy('arrive_date', 'desc')->limit(1);
            // }]);
    	return Datatables::of($driver)
	        ->addColumn('action', function($u){
                $action = [];
                $action[] = \Form::lbButton(
                    "school/driver/{$u->id}/edit", 
                    'get',
                    trans('general.edit'),
                    ["class" => "btn btn-xs btn-info"]
                )->toHtml();
                
                if ($u->trips_count >0)
                {
                    $onclick = "alert('".trans('driver.Driver_has_data_the_trip')."'); return false;";
                }
                else
                {
                     $onclick =  "return confirm('".trans('driver.Are_you_sure?')."')";
                }
                $action[] = \Form::lbButton(
                     route('driver.destroy', [$u->id]),
                    'delete',
                    trans('general.delete'),
                    [
                        "class" => "btn btn-xs btn-danger",
                        "onclick" =>  $onclick
                    ]
                )->toHtml();
                $action[] = \Form::lbButton(
                    url('school/driver/'. $u->id .'/change_password'),
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

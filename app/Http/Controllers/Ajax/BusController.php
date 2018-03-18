<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Bus;
use App\Models\Driver;
use App\Models\SchoolAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Trip;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school = SchoolAdmin::where('id', Auth::user()->profile_id)->first()->school_id;

        $bus = Bus::where('school_id', $school)->get();
        return Datatables::of($bus)
            ->addColumn('action', function($b){
                $action = [];
                $action[] = \Form::lbButton(
                    "school/bus/{$b->id}/edit",
                    'get',
                    trans('general.edit'),
                    ["class" => "btn btn-xs btn-info"]
                )->toHtml();

                $trip = Trip::where('bus_id', $b->id)->first();
                if (count($trip)>0)
                {
                    $onclick = "return alert('".trans('bus.bus_has_trip_you_have_to_clear')."')";
                }
                else
                {
                    $onclick = "return confirm('".trans('bus.Are_you_sure?')."')";
                } 
                $action[] = \Form::lbButton(
                     route('bus.destroy', [$b->id]),
                    'delete',
                    trans('general.delete'),
                    [
                        "class" => "btn btn-xs btn-danger",
                        "onclick" => $onclick
                    ]
                )->toHtml();

                // $action[] = \Form::lbButton(
                //     route('bus.destroy', [$b->id]),
                //     'delete',
                //     trans('general.delete'),
                //     [
                //         "class" => "btn btn-xs btn-danger",
                //         "onclick" => "return confirm('".trans("bus.Are you sure?")."')"
                //     ]
                // )->toHtml();
                return implode(' ', $action);
            })

            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

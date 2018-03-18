<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use DB;
use App\Models\StudentTrip;
use App\Models\Student;
use Carbon\Carbon;
class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_tmp = [];
        $data = [];
        $trip_info = [];
        $trip = Trip::with(['buses' => function($query){
                        $query->orderBy('name', 'asc');
                    }])
                    ->select('id', 'lat', 'long', 'started_at', 'bus_id', 'type')
                    ->whereDate('arrive_date', DB::raw('CURDATE()'))
                    ->where('started_at', '<>', NULL)
                    ->where('lat', '<>', NULL)
                    ->where('long', '<>', NULL)
                    ->orderBy('started_at', 'DESC')
                    ->get();
        if (!empty($trip))
        {
            foreach ($trip as $r)
            {
                $data_tmp[$r->bus_id][] = [
                        'id' => $r->id,
                        'type' => ($r->type == 1) ? "Về nhà" : "Tới trường",
                        'bus_number' => $r->buses->bus_number,
                        'lat' => $r->lat, 
                        'lng' => $r->long, 
                        'bus_name' => $r->buses->name
                    ];
            }
            foreach ($data_tmp as $k => $r)
            {
                $trip_info[] = [
                        'id' => $r[0]['id'],
                        'lat' => $r[0]['lat'],
                        'lng' => $r[0]['lng'],
                        'type' => $r[0]['type'],
                        'bus_name' => $r[0]['bus_name'],
                        'bus_number' => $r[0]['bus_number'],
                        'total_pending' => StudentTrip::where('trip_id', $r[0]['id'])->where('status', 3)->count(),
                        'received' => StudentTrip::where('trip_id', $r[0]['id'])->where('status', 4)->count(),
                        'fabricate' => StudentTrip::where('trip_id', $r[0]['id'])->where('status', 5)->count() ,
                        'leave' => StudentTrip::where('trip_id', $r[0]['id'])->where('status', 6)->count(),
                    ];
                // $tmp[] = [
                //         'id' => $r[0]['id'], 'lat'=> rand(10,100), 'lng'=> rand(10,100),
                //     ];
            }
        }
        usort($trip_info, function($a, $b) {
            return $a["bus_name"] - $b["bus_name"];
        });
        return ['trip_info' => $trip_info];
    }

    public function getDataPopup(Request $request)
    {
        $timeStart = microtime(true);
        $trips = Trip::with('departures', 'students' , 'types', 'locations')->findOrFail($request->trip_id);
        return $trips;   
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

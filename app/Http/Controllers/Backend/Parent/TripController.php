<?php

namespace App\Http\Controllers\Backend\Parent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Student;
use Auth;
use Carbon\Carbon;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status = 4)
    {
        $dt = Carbon::now();
        //Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'vi');
        //setlocale(LC_TIME, config('app.locale'));
        $date = $dt->formatLocalized('%A %d %B %Y');
        $trip_date = Carbon::now()->toDateString();
        //$trip_date = Carbon::create(2017,4,29)->toDateString();
        $parent_id = Auth::user()->profile->id;
        $trips = Trip::ofParent($parent_id)->infoByDate($trip_date, $parent_id)->get();
                
        if ($trips->first() != NULL)
        {
            foreach ($trips as $trip)
            {
                foreach ($trip->students as $student)
                {   
                    //KT lai hoc sinh co thuoc parent dang login ko
                    if ($student->parents->first() != NULL )
                    {   
                        $departure_id = $student->departure->id;
                        $trip_departure = Trip::with(['departures' => function ($query) use ($departure_id) {
                            $query->where('departure_id',$departure_id);
                        }])->find($trip->id);

                        $departures = $trip_departure->departures;
                        
                        if ($departures->first() != NULL)
                        {
                            $arrive_time = $trip_departure->departures[0]->pivot->arrive_time;
                            $custom_arr_time = Carbon::parse($arrive_time)->format('H:i');

                            $data[] = [
                                'trip_id' => $trip->id,
                                'active_at' => $trip->active_at,
                                'finish_at' => $trip->finish_at,
                                'bus_name' => $trip->buses->name, 
                                'driver_name' => $trip->drivers[0]->full_name,
                                'driver_id' => $trip->drivers[0]->users[0]->id,
                                'phone_number' => $trip->drivers[0]->phone_number,
                                'hotline' => $student->classes[0]->school->phone_numbers,
                                'type' => $trip->types->name,
                                'student_name' => $student->full_name,
                                'arrive_time' => $custom_arr_time,
                                'status' => $student->pivot->status,
                                'avatar_id' => $student->avatar_id,
                                'student_lat' => $student->departure->lat,
                                'student_long' => $student->departure->long,
                                'trip_lat' => $trip->lat,
                                'trip_long' => $trip->long,
                                'departure_name' => $student->departure->name
                            ];  
                        }                               
                    }
                }    
            }
            
            return view('parent.trip.detail',['data' => $data, 'date' => $date, 'trips' => $trips ]);
         }
        else 
        {
            return view('parent.trip.detail', ['date' => $date]);
        }     
    }

    public function findBus()
    {
        $trip_date = Carbon::now()->toDateString();
        //$trip_date = Carbon::create(2017,4,29)->toDateString();
        $parent_id = Auth::user()->profile->id;
        $trips = Trip::ofParent($parent_id)->infoByDate($trip_date, $parent_id)->get();
        try
        {
            foreach ($trips as $trip)
            {
                foreach ($trip->students as $student)
                {   
                    //KT lai hoc sinh co thuoc parent dang login ko
                     if($student->parents->first() != NULL )
                    {   
                        $departure_id = $student->departure->id;
                        $trip_departure = Trip::with(['departures' => function ($query) use ($departure_id) {
                            $query->where('departure_id',$departure_id);
                        }])->findOrFail($trip->id);

                        $test = $trip_departure->departures;
                        
                        if ($test->first() != NULL)
                        {
                            $arrive_time = $trip_departure->departures[0]->pivot->arrive_time;
                            $data_ajax[] = [
                                'trip_id' => $trip->id,
                                'active_at' => $trip->active_at,
                                'finish_at' => $trip->finish_at,
                                'bus_name' => $trip->buses->name, 
                                'driver_name' => $trip->drivers[0]->full_name,
                                'phone_number' => $trip->drivers[0]->phone_number,
                                'hotline' => $student->classes[0]->school->phone_numbers,
                                'type' => $trip->types->name,
                                'student_name' => $student->full_name,
                                'arrive_time' => $arrive_time,
                                'status' => $student->pivot->status,
                                'avatar_id' => $student->avatar_id,
                                'student_lat' => $student->departure->lat,
                                'student_long' => $student->departure->long,
                                'trip_lat' => $trip->lat,
                                'trip_long' => $trip->long,
                            ];  
                        }      
                               
                    }
                   
                }

            }   
            // echo "<pre>";
            // print_r ($data_ajax);
            // echo "</pre>";

            return response()->json($data_ajax);
        }
        catch (\Exception $e)
        {
            dd($e->getMessage());
        } 
    
            ///return view('parent.trip.detail',['data' => $data, 'date' => $date ]);
       
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

    public function detail($id)
    {   
        $trip_date = Trip::findOrFail($id);
        // $trips = Trip::with('buses')->with('drivers')->where('arrive_date', $trip->arrive_date)->get();
        $parent_id = Auth::user()->profile->id;
        $trips = Trip::with('drivers')->ofParent($parent_id)->infoByDate($trip_date->arrive_date, $parent_id)->get();
        foreach ($trips as $trip)
        {
            $trips_id[] = $trip->id;
        }

        
        // $students = Student::with('trips.buses', 'parents')->whereHas('parents', function ($query) use ($parent_id) {
        //     $query->where('parents.id', $parent_id);
        // })->whereHas('trips', function ($trip_query) use ($trips_id) {
        //     $trip_query->whereIN('trips.id', $trips_id);
        // })->whereHas('trips', function ($trip_query_date) use ($trip_date) {
        //     $trip_query_date->where('trips.arrive_date', $trip_date->arrive_date);
        // })
        // ->get();
        
        // $departure_id = Student::findOrFail(21)->departure_id;
        // $trip = Trip::with('departures')->whereHas('departures', function ($query) use ($departure_id) {
        //     $query->where('departure_id', $departure_id)->where('trip_id', 1);
        // })->get();

        $student = Student::findOrFail(21);
        $departure_id = $student->departure_id;
        $trip = Trip::with('departures')->whereHas('departures', function ($query) use ($departure_id) {
            $query->where('departure_id', $departure_id);
        })->where('trips.id',1)->first();
        foreach ($trip->departures as $item)
        {
            if ($item->id == $departure_id)
            {
                $arrive_time = $item->pivot->arrive_time;
            }
        }
        
        // $test = Student::getArriveTime(21,$id);
        // //return $arrive_time;
        // echo "<pre>";
        // print_r($test);
        // echo "</pre>";
        return view('parent.trip.detail', [
            'id' => $id,
            // 'arrive_date' => $trip_date->arrive_date,
            // 'students' => $students,
            'trips' => $trips
        ]);
    }
}

<?php

namespace App\Http\Controllers\Backend\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentTrip;
use App\Models\Trip;
use App\Models\Departure;
use App\Models\TripDeparture;
use App\Models\Classes;
use App\Models\Bus;
use App\Models\Driver;

use Auth,DB,Validator;
class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('backend.trip.index');
    }

    public function getStudentTrip($id)
    {
        return view('backend.trip.student_trip',['trip_id' => $id]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $school_id = Auth::user()->profile->school_id;
        $class = Classes::where('school_id',$school_id)->get();
        $bus = Bus::where('school_id', $school_id)->get();
        $dirver = Driver::where('school_id', $school_id)->get();
        $dp = Departure::where('school_id', $school_id)->get();
        if ( count($class) >0 && count($bus) >0 && count($dirver) >0 && count($dirver) >0 && count($dp) >0)
        {
            return view('backend.trip.add');
        }
        else
        {
            return redirect(url('school/trip'))->with('confirm',trans('alert.you_can_not_create_trip'));
        }
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try
        {   
            $arrive_time =  $request->arrive_time;
            $trip_info = $request->trip_info;
            $postion_student = $request->postion_student;
            $pay_back_time = $request->pay_back_time;
            foreach ($trip_info['date'] as $r)
            {
                $trip =  new Trip;
                $trip->name = $trip_info['name'];
                $trip->bus_id = $trip_info['bus'];
                $trip->arrive_date = date("Y/m/d", strtotime($r));
                $trip->type = $trip_info['trip_type'];
                $trip->school_id = Auth::user()->profile->school_id;
                $trip->created_by = Auth::user()->id;
                if ($trip_info['started_at'] != 'NULL')
                {
                    $trip->started_at = $trip_info['started_at'];
                }
                $trip->save();
                $trip->drivers()->attach($trip_info['driver'],[
                        'created_by' => Auth::user()->id,
                        'created_at' => date("y-m-d h:i:s")
                    ]);
                foreach ($request->student_trip as $r)
                {   
                    $student = Student::find($r['id']);
                    if ($trip_info['trip_type'] == 1) 
                    {
                        foreach ($postion_student as $k => $p) 
                        {
                            if ($p['student_id'] == $r['id']) 
                            {
                                $trip->students()->attach($p['student_id'], [
                                    'pick_up_id' => $p['postion_pay_back'],
                                    'drop_off_id' => $student->departure_id2,
                                    'created_by' => Auth::user()->id,
                                    'created_at' => date("y-m-d h:i:s")
                                ]);
                            }
                        }   
                    }
                    else 
                    {
                        foreach ($postion_student as $k => $p) 
                        {   
                            if ($p['student_id'] == $r['id']) 
                            {
                                $trip->students()->attach($p['student_id'],[
                                    'drop_off_id' => $p['postion_pay_back'],
                                    'pick_up_id' => $student->departure_id,
                                    'created_by' => Auth::user()->id,
                                    'created_at' => date("y-m-d h:i:s")
                                ]);
                            }
                        }   
                    }
                }
                foreach ($arrive_time as $r)
                {   
                    $trip->departures()->attach($r['id'],[
                            'arrive_time' => $r['time'],
                            'created_by'=>Auth::user()->id,
                            'created_at' => date("y-m-d h:i:s")
                        ]);
                }

                foreach ($pay_back_time as $r)
                {
                    $trip->departures()->attach($r['id'],[
                            'arrive_time' => $r['time'],
                            'created_by'=>Auth::user()->id,
                            'created_at' => date("y-m-d h:i:s")
                        ]);
                }
                DB::commit();
            }    
            return ['code' =>200];
        }
        catch(Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }
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
        try
        {
            $trip = Trip::with('drivers')->find($id);
            $departure = TripDeparture::where('trip_id', $id)->get();
            foreach ($departure as $r)
            {
                $dp[] = ['id' => $r->departure_id ,'time' => $r->arrive_time];
            }
            $dp =json_encode($dp);
            foreach ($trip->students as $r)
            {
                $select_student[] =  ['name' => $r->full_name,'id' => $r->id];
            }
            $select_student = json_encode($select_student);
            return view('backend.trip.edit_trip_info',[
                    'select_student' => $select_student,
                    'departure' => $dp, 
                    'trip' => $trip,
                ]);
        }
        catch(\Epception $e)
        {
            dd($e->getMessage());
        }
       
    }

    public function getEditDeparture($id)
    {   
        $trip = Trip::with('departures')->find($id);
        return view('backend.trip.edit_departure',['trip_id'=> $id, 'dp' => $trip->departures]);
    }

    public function postEditDeparture(Request $request, $id)
    {   
        $trip = Trip::with('departures')->find($id);
        $array = [];
        foreach ($trip->departures as $k => $r)
        {   
            $name = 'dp'.$r->id;
            $array = array_merge_recursive($array, [$name => ['required', 'date_format:H:i']]);
        }  
        // dd($request->all());
        $messages = [
            'required' => trans("validate.the_file_required"),
            'date_format' => trans("validate.the_date_format")
        ];
        $validator = Validator::make($request->all(), $array, $messages);

        if ($validator->fails()) 
        {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        foreach ($trip->departures as $k => $r)
        {   
            $name = 'dp'.$r->id;
            $trip->departures()->sync([$r->id => [ 
                                                    'arrive_time' => $request->$name,
                                                    'updated_by' => Auth::user()->id
                                                ] 
                                        ], false); 
        }
        return redirect(url('school/trip'))->with('confirm',trans('alert.edit_success'));
    }

    public function getEditPayBack($id)
    {   
        try
        {
            $trip = Trip::findOrFail($id);
            $st = Trip::findOrFail($id)->students;
            foreach ($st as $r)
            {
                $pick_up_tmp[] = $r->pivot->pick_up_id;
                $drop_off_tmp[] = $r->pivot->drop_off_id;
            }
            $pick_up_id = array_unique($pick_up_tmp);
            foreach ($pick_up_id as $r)
            {
                $pick_up_info[] = ['name' => Departure::find($r)->name,'id'=> $r];
            }
            $drop_off_id = array_unique($drop_off_tmp);
            foreach ($drop_off_id as $r) 
            {
                $drop_off_info[] = ['name' => Departure::find($r)->name, 'id' => $r];
            }
            if ($trip->type == 1)
            {
                return view('backend.trip.edit_pay_back',[
                        'trip_id'=> $id,
                        'st_dp'=> $st,
                        'pay_back' => $pick_up_info,
                        'type'=>1
                    ]);
            }
            else
            {
                return view('backend.trip.edit_pay_back',[
                        'trip_id'=> $id,
                        'st_dp'=> $st,
                        'pay_back' => $drop_off_info,
                        'type'=>2
                    ]);
            }
        }
        catch(\Exception $e)
        {
            dd($e->getMessage());
        }
        
    }

    public function postEditPayBack(Request $request, $id)
    {   
        $trip = Trip::with('students')->findOrFail($id);
        foreach ($trip->students as $r)
        {   
            $name = 'radio'.$r->id;
            if ($trip->type == 1)
            {   
                $trip->students()->sync([$r->id => [
                            'pick_up_id'=> $request->$name,
                            'updated_by' => Auth::user()->id
                        ]
                    ],false);
            }
            else
            {
                $trip->students()->sync([$r->id => [
                            'drop_off_id'=> $request->$name,
                            'updated_by' => Auth::user()->id
                        ]
                    ],false);
            }
        }
        return redirect(url('school/trip'))->with('confirm',trans('alert.edit_success'));
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
        $trip = Trip::with('drivers')->find($id);
        $trip->name = $request->name;
        $trip->bus_id = $request->bus;
        $trip->arrive_date = date("Y/m/d", strtotime($request->date));
        $trip->started_at = $request->started_at;
        $trip->updated_by = Auth::user()->id;
        $trip->save();
        $trip->drivers()->detach();
        $trip->drivers()->attach([$request->driver=> ['updated_by' => Auth::user()->id]]);
        return redirect('school/trip')->with('confirm',trans('alert.edit_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $trip = Trip::find($id);
        if ($trip->finish_at == NULL && $trip->active_at == NULL || $trip->finish_at != NULL && $trip->active_at != NULL ) 
        {
            $trip->delete();
            $trip->departures()->detach();
            $trip->students()->detach();
            $trip->drivers()->detach();
            return redirect(url('/school/trip'))->with('confirm', trans('trip.trip_delete_success'));
        }
        if ($trip->active_at != NULL && $trip->finish_at == NULL) 
        {
            return redirect()->back()->with('confirm', trans('trip.trip_active_you_dont_not_delete'));
        }
    }
}

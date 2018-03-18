<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StudentTrip;
use App\Models\Student;
use App\Models\Trip;
use App\Models\Departure;
use App\Models\TripDeparture;
use Yajra\Datatables\Facades\Datatables;

use App\Models\User;
use Auth,DB;
use App\Models\Parents;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school_id = Auth::user()->profile->school_id;
        $trips = Trip::select('trips.id', 'trips.name', 'trips.bus_id', 'trips.arrive_date', 'trips.type')
            ->where('trips.school_id', $school_id)
            ->with('buses')
            ->with('types');
            // ->whereHas('buses', function($query) use($school_id){
            //     $query->where('school_id', $school_id);
            // });
        return Datatables::of($trips)
            ->addColumn('action', function($u){

                $action = [];
                $action[] = \Form::lbButton(
                    "school/student_trip/".$u->id,
                    'get',
                    trans('general.student_trip'),
                    ["class" => "btn btn-xs btn-primary"]
                )->toHtml();

                $action[] = \Form::lbButton(
                    "school/trip/{$u->id}/edit",
                    'get',
                    trans('general.edit_trip_info'),
                    ["class" => "btn btn-xs btn-info"]
                )->toHtml();

                $action[] = \Form::lbButton(
                    "school/trip/edit_departure/{$u->id}",
                    'get',
                    trans('general.edit_departure'),
                    ["class" => "btn btn-xs btn-success"]
                )->toHtml();

                $action[] = \Form::lbButton(
                    "school/trip/edit_pay_back/{$u->id}",
                    'get',
                    trans('general.edit_pay_back'),
                    ["class" => "btn btn-xs btn-warning"]
                )->toHtml();

                $action[] = \Form::lbButton(
                    url('school/trip/' . $u->id),
                    'delete',
                    trans('general.delete'),
                    [
                        "class" => "btn btn-xs btn-danger",
                        "onclick" => "return confirm('Are you sure?')"
                    ]
                )->toHtml();
                return implode(' ', $action);
            })
            ->make(true);
    }

    public function studentTrip($trip_id)
    {
        $student = Trip::find($trip_id)->students;
        return Datatables::of($student)
            ->make(true);
    }

    public function getStudentByClass(Request $request)
    {   
        try
        {
            $class_id = $request->class_id;
            $student = Student::whereHas('classes', function ($query) use ($class_id) {
                    $query->where('id', $class_id);
            })->get();

            foreach ($student as $r)
            {
                $data[] = ['id' => $r->id ,'name' => $r->full_name, 'code' => $r->code,'dp_id'=> @$r->departure->id];
            }
            return ['code'=> '200','data'=> $data];
        }
        catch(\Exception $e)
        {
            dd($e->getMessage());
        }
        
    }

    public function getStudent(Request $request)
    {
        $student = $request->student;
        $trip_type = $request->trip_type;
        foreach ($student as $r)
        {
            $tmp = @Student::with('departure', 'classes', 'departure2')->find($r['id']);
            if ($trip_type == 1)
            {
                $tmp_id[] =  @$tmp->departure2->id; // pick down
            }
            else if($trip_type == 2)
            {
                $tmp_id[] =  @$tmp->departure->id; 
            }
            
        }
        $departure_id = array_unique($tmp_id);
        foreach ($departure_id as $r)
        {   
            $name = @Departure::find($r)->name;
            $dp[] = ['departure' => $name,'id' => $r];
        }
        return $dp;
    }


    public function getPayBack(Request $request) 
    {   
        $school_id = Auth::user()->profile->school_id;
        $data = $request->data;
        $dp = Departure::where('school_id', $school_id)->get();
        foreach ($dp as $v) 
        {
            $dp_tmp[] = ['name' => $v->name, 'id' => $v->id];
        }
        foreach ($data as $k => $v) {
            $data_tmp[] = ['name' => $v['departure'], 'id' => $v['id']];
        }

        foreach ($dp_tmp as $k => $v)
        {
            foreach ($data_tmp as $s_k => $s_v)
            {
                if ($s_v['id'] == $v['id'])
                {
                    unset($dp_tmp[$k]);
                }
            }
        }
      
        return $dp_tmp;

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
       /* DB::beginTransaction();
        try
        {   
            $student = $request->student;
            $date_trip = $request->date_trip;
            $arrive_time =  $request->arrive_time;

            foreach ($date_trip as $r)
            {
                $trip = Trip::find($id);
                $trip->name = $request->name_trip;
                $trip->bus_id = $request->bus_trip;
                $trip->type = $request->trip_type;
                $trip->arrive_date =  date("Y/m/d", strtotime($r));
                $trip->updated_by = Auth::user()->id;
                $trip->save();
                $trip->drivers()->detach();
                $trip->drivers()->attach($request->driver_trip);
                foreach ($student as $r)
                {   
                    $student_id[] = $r['id'];
                }
                $trip->students()->detach();
                $trip->students()->attach($student_id);
                $trip->departures()->detach();
                foreach ($arrive_time as $k =>  $r)
                {       
                    $trip->departures()->attach($r['id'],['arrive_time' => $r['time']]);
                }
            }   
            DB::commit(); 
            return ['code' =>200];
        }
        catch(Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }*/
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
    }
}

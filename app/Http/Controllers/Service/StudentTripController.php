<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ChangeStudentStatusRequest;
use App\Models\Push_device;
use App\Models\Push_user_device;
use App\Models\Trip;
use App\Models\Student;

class StudentTripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function update(ChangeStudentStatusRequest $request, $student_id, $trip_id)
    {
        $trip = Trip::findOrFail($trip_id);
        $trip->students()->updateExistingPivot($student_id, ['status' => $request->status_id]);

        // this parameter can be removed later
        if (isset($request->nopush) && $request->nopush == 1)
        {
            #    
        }
        else
        {
            $this->_sendNotification($student_id, $request->status_id);
        }

        return response([
            'description' => 'success'
        ]);
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

    public function changeAll(Request $request, $trip_id)
    {
        $status_id = $request->status_id;
        $student_ids = json_decode($request->student_id);
        $trip = Trip::findOrFail($trip_id);
        foreach ($student_ids as $student_id) 
        {
            $trip->students()->updateExistingPivot($student_id, ['status' => $status_id]);
            $this->_sendNotification($student_id, $request->status_id);
        }
        return response([
            'description' => 'success'
        ]);
    }

    private function _sendNotification($student_id, $status)
    {
        $student = Student::with('parents.users')->where('id', $student_id)->first();

        if ($status == 4)
        {
            $title = 'Cháu ' . $student->full_name . ' đã được đón lên xe.'; 
        }
        else if ($status == 5)
        {
            $title = 'Cháu ' . $student->full_name . ' đã được trả xuống xe.';
        }
        else
        {
            return null;
        }

        foreach ($student->parents as $parent) 
        {
            if (isset($parent->users[0]))
            {
                $user_device = Push_user_device::where('user_id', $parent->users[0]->id)->with('devices')->get();
                foreach ($user_device as $arr) 
                {
                    $ids = $arr->devices->id;
                    $device = Push_device::find(@$ids);
                    if ($device)
                    {
                        // $device->send($title, 'desc');
                        $device->send('', $title);
                    }
                }
            }
        }
    }
}

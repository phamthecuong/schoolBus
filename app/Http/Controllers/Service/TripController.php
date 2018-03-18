<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Service\IdRequest;
use App\Transformer\TripTransformer;

use App\Models\Student;
use App\Models\TripLocation;
use App\Models\TripDeparture;
use App\Models\Trip;
use App\Models\User;
use App\Models\Parents;
use App\Models\Push_device;
use App\Models\Push_user_device;
use App\Models\ParentStatus;
use App\Models\Departure;
use Auth;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class TripController extends ApiController
{
    public function detail(IdRequest $request)
    {
        $id = $request->id;
        $user = Auth::user();
        if ($user && $user->isDriver()) {
            $trip = Trip::detail()->find($id);
            return response([
                'data' => $trip
            ]);
        }
        else
        {
            return $this->errorBadRequest([
                'errors' => 'Invalid role'
            ]);
        }
    }

    public function detailForParent(Request $request)
    {
        $id = $request->id;
        $user = Auth::user();
        if ($user->isParent()) {
            $parent_id = $user->profile->id;
            $trip = Trip::detailForParents($parent_id)->where('id', $id)->get();

            return $this->responses(new TripTransformer($trip));
        }
        else
        {
            return $this->errorBadRequest([
                'errors' => 'Invalid role'
            ]);
        }
    }

    // public function sendLocation(Request $request)
    // {
    //     $this->validate( $request,[
    //         'trip_id' => 'required',
    //         'lat' => 'required',
    //         'long' => 'required'
    //     ]);
    //     $trip_location = new TripLocation;
    //     $trip_location->fill($request->all());
    //     $trip_location->save();

    //     $trip_id = $request->trip_id;
    //     //now location
    //     $trip_lat = $request->lat;
    //     $trip_long = $request->long;

    //     $this->checkPush($trip_id, $trip_lat, $trip_long);

    //     return response([
    //         'description' => 'success'
    //     ]);
        
    // }

    public function getLocation($id)
    {
        $trip = Trip::find($id);
        if ($trip)
        {
            return response([
                'lat' => $trip->lat,
                'long' => $trip->long
            ]);
        }
        else
        {
            return $this->errorBadRequest([
                'errors' => 'Invalid trip'
            ]);
        }
        
    }

    // public function forgotStudent(Request $request)
    // {
    //     $this->validate( $request,[
    //         'trip_id' => 'required',
    //         'student_id' => 'required'
    //     ]);
    //     $trip = Trip::findOrFail($request->trip_id);
    //     $student = Student::with('parents.users')->findOrFail($request->student_id);
    //     foreach ($student->parents as $parent) {
    //         $ids = $parent->users[0]->id;
    //         $this->sendNotification($ids, 'Xe '. $trip->buses->name .' quên em '.$student->full_name);
    //     }
    //     return response([
    //         'description' => 'success'
    //     ]);
        
    // }

    // public function finishdDeparture(Request $request)
    // {
    //     $this->validate( $request,[
    //         'trip_id' => 'required',
    //         'departure_id' => 'required'
    //     ]);

    //     $trip_id = $request->trip_id;
    //     $departure_id = $request->departure_id;
    //     $departure = TripDeparture::where([
    //         ['trip_id', $trip_id],
    //         ['departure_id', $departure_id]
    //     ])->first();
    //     if ($departure)
    //     {
    //         if (!$departure->finish_at)
    //         {
    //             $departure->finish_at = date('Y-m-d H:i:s');
    //             $departure->save();
    //             return response([
    //                 'description' => 'success'
    //             ]);
    //         }
    //         else
    //         {
    //             return $this->errorBadRequest([
    //                 'errors' => 'Departure finished'
    //             ]);
    //         }
    //     }
    //     else
    //     {
    //         return $this->errorBadRequest([
    //             'errors' => 'Invalid departure'
    //         ]);
    //     }
        
    // }

    public function updateLocation(Request $request)
    {
        $json =  file_get_contents("php://input"); // or whatever json data
        $json = json_decode($json);
        $headers = getallheaders();
        $trip_id = $headers['trip_id'];
        $trips = Trip::find($trip_id);
        if (isset($trips))
        {
            foreach ($json as $array)
            {
                if (array_key_exists('latitude', $array) && array_key_exists('longitude', $array) && array_key_exists('time', $array)  )
                {
                    $trip_location = new TripLocation;
                    $trip_location->trip_id = $trip_id;
                    $trip_location->lat = $array->latitude;
                    $trip_location->long = $array->longitude;
                    $carbon = Carbon::createFromTimestamp((int)($array->time/1000))->toDateTimeString();
                    $trip_location->created_at = $carbon;
                    $trip_location->save();
                    $this->checkPush($trip_id, $array->latitude, $array->longitude);   
                }
                else 
                {
                    return response ([
                        'description' => 'missing data'
                    ]);        
                }
                                                
            }
            
            return response ([
                'description' => 'success'
            ]);    
        }
        else 
        {
            return response ([
                'description' => 'fail'
            ]);
        }     
    }

    public function finish($id)
    {
        $trip = Trip::findOrFail($id);
        if (!$trip->finish_at)
        {
            $trip->finish_at = date('Y-m-d H:i:s');
            $trip->save();
            return response([
                'description' => 'success'
            ]);
        }
        else
        {
            return $this->errorBadRequest([
                'errors' => 'Trip is finished'
            ]);
        }
    }

    private function _distance($lat1, $lon1, $lat2, $lon2) 
    {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;
        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;
        return $km * 1000;
    }

    public function createParentStatusTable($id, $trip_id, $departure_id, $distance_id)
    {
        $parent_status = new ParentStatus();
        $parent_status->parent_id = $id;
        $parent_status->trip_id = $trip_id;
        $parent_status->distance_id = $distance_id;
        $parent_status->departure_id = $departure_id;
        $parent_status->save();
    }

    public function createForgotParentStatusTable($id, $trip_id, $departure_id, $forgot = 1)
    {
        $parent_status = new ParentStatus();
        $parent_status->parent_id = $id;
        $parent_status->trip_id = $trip_id;
        $parent_status->departure_id = $departure_id;
        $parent_status->forgot = $forgot;
        $parent_status->save();
    }

    public function sendNotification($id, $title)
    {
        $user_device = Push_user_device::where('user_id', $id)->with('devices')->get();
        foreach ($user_device as $arr) 
        {
            $ids = $arr->devices->id;
            $device = Push_device::find(@$ids);
            if ($device)
            {
                $device->send('SchoolBus', $title);
            }
        }
    }

    public function checkPush($trip_id, $trip_lat, $trip_long)
    {
        $trip = Trip::with('departures', 'students.parents.users', 'students.parents.distances', 'drivers.users')->find($trip_id);

        // return $trip;

        foreach ($trip->departures as $departure)
        {
            if ($departure->pivot->finish_at)
            {
                $df_lat = $departure->lat;
                $df_long = $departure->long;
                $departure_id_finished = $departure->id;
            }
            if (!$departure->pivot->finish_at)
            {
                $dnf_lat = $departure->lat;
                $dnf_long = $departure->long;
                $departure_id_not_finish = $departure->id;
                break;
            }
        }

        //push forgot student
        if (isset($departure_id_finished))
        {
            // return $this->_distance($trip_lat, $trip_long, $df_lat, $df_long);
            if ($this->_distance($trip_lat, $trip_long, $df_lat, $df_long) >= 100)
            {
                foreach ($trip->students as $student) 
                {
                    if (count($student) == 0)
                    {
                        continue;
                    }

                    //push forgot pick up students
                    if ($student->pivot->pick_up_id == $departure_id_finished)
                    {
                        if ($student->pivot->status == 3)
                        {
                            //push for driver
                            $ud_id = $trip->drivers[0]->users[0]->id;
                            $forgot = ParentStatus::where([
                                ['parent_id', $ud_id],
                                ['trip_id', $trip_id],
                                ['departure_id', $departure_id_finished],
                                ['forgot', 1]
                            ])->first();
                            if (!$forgot)
                            {
                                $this->sendNotification($ud_id, 'Bạn vừa quên không đón học sinh');
                                $this->createForgotParentStatusTable($ud_id, $trip_id, $departure_id_finished);
                            }

                            //push for parent
                            foreach ($student->parents as $parent) 
                            {
                                if (count($parent) == 0)
                                {
                                    continue;
                                }
                                $up_id = $parent->users[0]->id;
                                $forgot = ParentStatus::where([
                                    ['parent_id', $up_id],
                                    ['trip_id', $trip_id],
                                    ['departure_id', $departure_id_finished],
                                    ['forgot', 1]
                                ])->first();
                                if (!$forgot)
                                {
                                    $this->sendNotification($up_id, 'Xe '. $trip->buses->name .' quên đón con của bạn');
                                    $this->createForgotParentStatusTable($up_id, $trip_id, $departure_id_finished);
                                }
                            }
                        }
                    }

                    //push forgot drop off students
                    if ($student->pivot->drop_off_id == $departure_id_finished)
                    {
                        if ($student->pivot->status == 4)
                        {
                            //push for driver
                            $ud_id = $trip->drivers[0]->users[0]->id;
                            $forgot = ParentStatus::where([
                                ['parent_id', $ud_id],
                                ['trip_id', $trip_id],
                                ['departure_id', $departure_id_finished],
                                ['forgot', 1]
                            ])->first();
                            if (!$forgot)
                            {
                                $this->sendNotification($ud_id, 'Bạn vừa quên không trả học sinh');
                                $this->createForgotParentStatusTable($ud_id, $trip_id, $departure_id_finished);
                            }

                            //push for parent
                            foreach ($student->parents as $parent) 
                            {
                                if (count($parent) == 0)
                                {
                                    continue;
                                }
                                $up_id = $parent->users[0]->id;
                                $forgot = ParentStatus::where([
                                    ['parent_id', $up_id],
                                    ['trip_id', $trip_id],
                                    ['departure_id', $departure_id_finished],
                                    ['forgot', 1]
                                ])->first();
                                if (!$forgot)
                                {
                                    $this->sendNotification($up_id, 'Xe '. $trip->buses->name .' quên trả con của bạn');
                                    $this->createForgotParentStatusTable($up_id, $trip_id, $departure_id_finished);
                                }
                            }
                        }
                    }
                }
            }
        }

        //push notification for parents
        foreach ($trip->departures as $departure)
        {
            if ($departure->pivot->finish_at)
            {
                continue;
            }
            $d_lat = $departure->lat;
            $d_long = $departure->long;
            foreach ($departure->students as $student) {
                foreach ($student->parents as $parent) 
                {
                    if (count($parent) == 0)
                    {
                        continue;
                    }
                    $length = count($parent->distances);
                    for ($i = 0; $i < $length; $i++) {
                        $count_distance = 0;
                        $status = ParentStatus::where([
                            ['trip_id', $trip_id],
                            ['parent_id', $parent->users[0]->id],
                            ['departure_id', $departure->id],
                            ['distance_id', $parent->distances[$i]->id]
                        ])->first();
                        if ($status)
                        {
                            $count_distance = 1;
                        }
                        
                        if ($i == 0 )
                        {
                            if ($this->_distance($trip_lat, $trip_long, $d_lat, $d_long) < $parent->distances[$i]->about && empty($count_distance))
                            {
                                $ids = $parent->users[0]->id;
                                $this->sendNotification($ids, 'Xe '. $trip->buses->name .' còn cách điểm dừng '. $parent->distances[$i]->about . 'm');
            
                                $this->createParentStatusTable($parent->users[0]->id, $trip_id, $departure->id, $parent->distances[$i]->id);
                            }
                        }
                        else 
                        {
                            if ($this->_distance($trip_lat, $trip_long, $d_lat, $d_long) > $parent->distances[$i-1]->about && $this->_distance($trip_lat, $trip_long, $d_lat, $d_long) <= $parent->distances[$i]->about && empty($count_distance))
                            {
                                $ids = $parent->users[0]->id;
                                $this->sendNotification($ids, 'Xe '. $trip->buses->name .' còn cách điểm dừng '. $parent->distances[$i]->about . 'm');
            
                                $this->createParentStatusTable($parent->users[0]->id, $trip_id, $departure->id, $parent->distances[$i]->id);
                            }
                        }
                    }
                }   
            }
        }

        //finish departure
        if (isset($departure_id_not_finish))
        {
            // return $this->_distance($trip_lat, $trip_long, $dnf_lat, $dnf_long);
            if ($this->_distance($trip_lat, $trip_long, $dnf_lat, $dnf_long) < 100)
            {
                $trip = Trip::findOrFail($trip_id);
                $trip->departures()->updateExistingPivot($departure_id_not_finish, ['finish_at' => date('Y-m-d H:i:s')]);
            }
        }
    }

    public function postNote($id, Request $request)
    {
        $trip = Trip::findOrFail($id);
        $trip->note = $request->note;
        $trip->save();
        return response([
            'description' => 'success'
        ]);
    }
}

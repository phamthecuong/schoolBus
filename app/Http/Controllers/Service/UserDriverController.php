<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Service\IdRequest;

use App\Models\Trip;
use App\Models\User;
use App\Models\TripLocation;
use App\Models\StudentTrip;
use Auth;

class UserDriverController extends ApiController
{
    public function listTrip(Request $request)
    {
        $date = $request->date;
        $user = Auth::user();
    	if ($user->isDriver()) {
            $driver_id = $user->profile->id;
    		$trip = Trip::ofDriver($driver_id)->infoTripForDriver($date)->get();

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

    public function activeTrip(IdRequest $request)
    {

        $id = $request->id;
        $trip = Trip::find($id);
        if ($trip)
        {
            if (!$trip->active_at && !$trip->finish_at)
            {
                $trip->active_at = date('Y-m-d H:i:s');
                $trip->save();
                return response([
                    'description' => 'success'
                ]);
            }
            elseif ($trip->active_at && !$trip->finish_at)
            {
                return $this->errorBadRequest([
                    'errors' => 'trip actived'
                ]);
            }
            else
            {
                return $this->errorBadRequest([
                    'errors' => 'trip finshed'
                ]);
            }
        }
        else
        {
            return $this->errorBadRequest([
                'errors' => 'Invalid trip'
            ]);
        }
    }

    public function pickUp(IdRequest $request)
    {
        $id = $request->id;
        $student_trip = StudentTrip::find($id);
        if ($student_trip)
        {
            $student_trip->status = 4;
            $student_trip->save();
            return response([
                'description' => 'success'
            ]);
        }
        else
        {
            return $this->errorBadRequest([
                'errors' => 'Invalid id'
            ]);
        }

    }

    public function getOff(IdRequest $request)
    {
        $id = $request->id;
        $student_trip = StudentTrip::find($id);
        if ($student_trip)
        {
            $student_trip->status = 5;
            $student_trip->save();
            return response([
                'description' => 'success'
            ]);
        }
        else
        {
            return $this->errorBadRequest([
                'errors' => 'Invalid id'
            ]);
        }

    }

    public function absense(IdRequest $request)
    {
        $id = $request->id;
        $student_trip = StudentTrip::find($id);
        if ($student_trip)
        {
            $student_trip->status = 6;
            $student_trip->save();
            return response([
                'description' => 'success'
            ]);
        }
        else
        {
            return $this->errorBadRequest([
                'errors' => 'Invalid id'
            ]);
        }

    }
}

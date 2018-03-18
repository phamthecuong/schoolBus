<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Requests\Service\AddStudentCodeRequest;
use App\Http\Controllers\Controller;
use App\Transformer\TripTransformer;
use Illuminate\Support\MessageBag;

use App\Models\Trip;
use App\Models\Parents;
use App\Models\Student;
use App\Models\Departure;
use App\Models\User;
use App\Models\Payment;
use App\Models\LBM_conversation;
use App\Models\LBM_conversation_user;
use App\Models\SchoolAdmin;
use Auth;

class UserParentController extends ApiController
{
    public function listTrip(Request $request)
    {
        $date = $request->date;
        $user = Auth::user();
        if ($user->isParent()) {
            $parent_id = $user->profile->id;
            $trip = Trip::ofParent($parent_id)->infoByDate($date, $parent_id)->get();

            return $this->responses(new TripTransformer($trip));
        }
        else
        {
            return $this->errorBadRequest([
                'errors' => 'Invalid role'
            ]);
        }
        // $students = $parent->students;
        // $student_ids = $students->pluck("id");

        // $trip = Trip::whereHas("student_bridges", function($query) use ($student_ids) {
        //     $query->whereIn("student_id", $student_ids);
        // })->with('types', 'buses', 'departures', 'students.users', 'students.parents', 'students.departure')->where('arrive_date', $date)->orderBy('arrive_date', 'desc')->get();
    }

    public function logs()
    {
        
        $user = Auth::user();
        if ($user->isParent()) {
            $user_id = $user->profile->id;
            $data = Student::whereHas('parents', function ($q) use ($user_id) {
                $q->where('parent_id', $user_id);
            })
            ->logs()
            ->get();

            return response([
                'data' => $data
            ]);
        }
        else
        {
            return $this->errorBadRequest([
                'errors' => 'Invalid role'
            ]);
        }
    }

    public function payment()
    {
        $user = Auth::user();
        if ($user->isParent()) {
            $parent_id = $user->profile->id;
            $payment = Payment::whereHas('students', function ($q) use ($parent_id) {
                $q->whereHas('parents', function ($q) use ($parent_id) {
                    $q->where('parent_id', $parent_id);
                });
            })
            ->paymentInfo()
            ->get();
            
            return $payment;
        }
        else
        {
            return $this->errorBadRequest([
                'errors' => 'Invalid role'
            ]);
        }
    }

    public function addStudentCode(AddStudentCodeRequest $request)
    {
        \DB::beginTransaction();
        try
        {
            $student = Student::where('code', $request->student_code)->first();
            if (!$student) 
            {
                $errors = new MessageBag(['student_code' => ['student_code is invalid']]);
                return $this->errorInvalid($errors);
            }
            else
            {
                $student_code = $request->student_code;

                $parent = Auth::user()->profile;
                $parent_id = Auth::user()->profile->id;
                $user_id = Auth::user()->id;
                if (count($parent->students()->wherePivot('student_id', $student->id)->wherePivot('parent_id', $parent_id)->get()) != 0)
                {
                    return $this->errorBadRequest([
                        'errors' => 'Already students'
                    ]);
                }
                $parent->students()->syncWithoutDetaching([$student->id]);
                $this->_addConversation($user_id, $student->id);
                \DB::commit();

                return response([
                    'description' => 'success'
                ]);
            }
        }
        catch (Exception $e)
        {
            \DB::rollBack();
        }
    }

    private function _addConversation($user_id, $student_id)
    {
        $school_id = Student::find($student_id)->classes[0]->school_id;
        $school_admin = SchoolAdmin::where('school_id', $school_id)->first()->users[0];
        $check = false;
        $data = LBM_conversation_user::select('conversation_id', \DB::raw('COUNT(*) as total_conversation'))
            ->whereIn('user_id', [$user_id, $school_admin->id])
            ->groupBy('conversation_id')->get();
        if (count($data) > 0)
        {
            foreach ($data as $data) 
            {
                if ($data->total_conversation == 2)
                {
                    $check = true;
                    break;
                }
            }
        }
        if (!$check) 
        {
            $conversation = new LBM_conversation();
            $conversation->save();
            $conversation->created_by = $user_id;
            $conversation->users()->sync([$user_id, $school_admin->id]);
        }
        else
        {
            return null;
        }
    }

    // public function studentDeparture()
    // {
    //     // $data = Auth::user()->profile->with('students.classes.school.departures')->where('id', Auth::user()->id)->first();
    //     $parent_id = Auth::user()->profile->id;

    //     $data = Student::with('departure')
    //     ->whereHas('parents', function ($query) use ($parent_id) {
    //         $query->where('parent_id', $parent_id);
    //     })
    //     ->get();

    //     return $data;
    // }

    // public function getDeparture($student_id)
    // {
    //     $school_id = Student::findOrFail($student_id)->classes[0]->school_id;
    //     $departure = Departure::where('school_id', $school_id)->get();
    //     return $departure;
    // }

    // public function postSettingDeparture(Request $request)
    // {
    //     $this->validate( $request,[
    //         'student_id' => 'required',
    //         'departure_id' => 'required'
    //     ]);
    //     $departure_id = $request->departure_id;
    //     $departure = Departure::find($departure_id);
    //     if ($departure)
    //     {
    //         $student = Student::findOrFail($request->student_id);
    //         $student->departure_id = $departure_id;
    //         $student->save();
    //         return response([
    //             'description' => 'success'
    //         ]);
    //     }
    //     else
    //     {
    //         return $this->errorBadRequest([
    //             'errors' => 'Invalid Departure'
    //         ]);
    //     }
    // }
}

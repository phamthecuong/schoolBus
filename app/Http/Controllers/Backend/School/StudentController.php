<?php

namespace App\Http\Controllers\Backend\School;
use App\Http\Requests\BackEnd\StudentRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Media;
use App\Models\Student;
use App\Models\StudentTrip;

use App\Models\Parents;
use App\Models\Classes;
use DB,Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.students.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.students.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        DB::beginTransaction();
        try
        {   
            $student = new Student;              
            $student->full_name = $request->full_name;
            $student->gender = $request->gender;
            if($request->departure_1 != "#")
            {
                $student->departure_id = $request->departure_1;    
            }
            if($request->departure_2 != "#")
            {
                $student->departure_id2 = $request->departure_2;    
            }
            
            if ($request->hasFile("image"))              
            {               
                $media = Media::saveFile($request->file("image"));            
                $student->avatar_id = $media->id;
            }
            $student->code = $request->code;
            $student->address = $request->address;
            $student->birthday = date("Y/m/d", strtotime($request->birthday));
            $student->created_by = \Auth::user()->id;
            $student->save();
            if($request->parent != NULL)
            {
                $student->parents()->sync($request->parent);
            }
           
            $student->classes()->attach($request->class);

            $user = new User;
            $user->profile_id = $student->id;
            $user->profile_type = 'student';
            $user->password = '';
            $user->email = '';
            $user->save();
            //$user->syncRoleByCodeName('student');
            DB::commit();
            return redirect(url('/school/student'));
        }
        catch(\Exception $e)
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
        $student = Student::with('parents','departure', 'departure2' ,'classes')->find($id);
        $user = User::where('profile_id', $id)->where('profile_type', 'student')->first();
        return view('backend.students.add', ['student' => $student, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request, $id)
    {   
        DB::beginTransaction();
        try
        {   
            $student = Student::find($id);              
            $student->full_name = $request->full_name;
            $student->gender = $request->gender;
            if($request->departure_1 != "#")
            {
                $student->departure_id = $request->departure_1;    
            }
            if($request->departure_2 != "#")
            {
                $student->departure_id2 = $request->departure_2;    
            }
            $student->birthday = date("Y/m/d", strtotime($request->birthday));
            if ($request->hasFile("image"))              
            {               
                $media = Media::saveFile($request->file("image"));            
                $student->avatar_id = $media->id;
            }
            $student->code = $request->code;
            $student->updated_by = \Auth::user()->id;
            $student->save();
            $student->parents()->sync($request->parent);
            // if($request->parent != "#")
            // {
            //     $student->parents()->attach($request->parent);
            // }
            $student->classes()->sync($request->class);

            DB::commit();
            return redirect(url('/school/student'));
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        DB::beginTransaction();
        try
        {   
            $trip = StudentTrip::where('student_id', $id)->get();
            if (count($trip) > 0)
            {
                return redirect()->back()->with([
                    'flash_level' => 'danger', 
                    'flash_message' => trans('validate.student_have_trip_cant_delete')
                    ]);
            }
            else
            {
                $student = Student::find($id);
                User::where('profile_id', $student->id)->where('profile_type', 'student')->first()->delete();
                $student->delete();
                $student->classes()->detach();
                DB::commit();
                return redirect(url('school/student'))->with('alert',trans('alert.delete_student_success'));
            }
           
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }   
    }
}

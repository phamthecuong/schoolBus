<?php

namespace App\Http\Controllers\Backend\School;

use App\Http\Requests\BackEnd\TeacherRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Classes;
use DB, Excel;
class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.teacher.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('backend.teacher.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeacherRequest $request)
    {   
        DB::beginTransaction();
        try
        {   
            $school_id = \Auth::user()->profile->school_id;
            $teacher = new Teacher();
            $teacher->full_name = $request->full_name;
            $teacher->school_id =  $school_id;
            $teacher->address =  $request->address;
            $teacher->birthday =  $request->birthday;
            $teacher->created_by = \Auth::user()->id;
            $teacher->save();
            
            $user = new User();
            $user->email = $request->email;
            $user->profile_id = $teacher->id;
            $user->profile_type = 'teacher';
            $user->password = '';
            $user->save();
           
            DB::commit();
            return redirect(url('/school/teacher'));
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
        $teacher  = Teacher::find($id);
        $user = User::where('profile_id', $id)->where('profile_type','teacher')->first();
        return view('backend.teacher.add', ['teacher' => $teacher, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeacherRequest $request, $id)
    {
        DB::beginTransaction();
        try
        { 
            $teacher =  Teacher::find($id);
            $teacher->full_name = $request->full_name;
            $teacher->address =  $request->address;
            $teacher->birthday =  $request->birthday;
            $teacher->updated_by = \Auth::user()->id;
            $teacher->save();

            $user =  User::where('profile_id', $id)->where('profile_type' ,'teacher')->first();
            $user->email = $request->email;
            $user->profile_id = $teacher->id;
            $user->profile_type = 'teacher';
            $user->password = '';
            $user->save();
          
            DB::commit();
            return redirect(url('school/teacher'));
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
            $class = Classes::where('teacher_id', $id)->get();
            if (count($class) > 0)
            {
                return redirect(url('school/teacher'));
            }
            else
            {   
                Teacher::find($id)->delete();
                $user = User::where('profile_id',$id)->where('profile_type','teacher')->get();
                $user[0]->delete();
                DB::commit();
                return redirect(url('school/teacher'))->with('alert',trans('confirm.delete_success'));
            }
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}

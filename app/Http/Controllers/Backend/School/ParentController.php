<?php

namespace App\Http\Controllers\Backend\School;

use App\Http\Requests\BackEnd\ParentRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Parents;
use App\Models\User;
use App\Models\StudentParent;
use App\Models\Media;
use App\Models\Classes;
use App\Models\Student;
use DB, Auth;

class ParentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.parent.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('backend.parent.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ParentRequest $request)
    {   

        DB::beginTransaction();
        try
        {   
            $school_id = Auth::user()->profile->school_id;
            $student = Student::where('code', $request->student_code)->schoolFilter($school_id)->first();
            if (!$student)
            {
                return redirect('/school/parent/create')->withInput()->with(['flash_level'=>'danger','flash_message'=>trans('auth.code_invalid')]);
            }
            else
            {    
                $parent = new Parents;
                $parent->full_name = $request->full_name;
                $parent->address = $request->address;
                $parent->phone_number = $request->phone;
                $parent->contact_email = $request->contact_email;
                $parent->created_by = \Auth::user()->id;
                $parent->save();
                $parent->students()->sync($student->id);
                $user = new User;
                $user->email = $request->email;
                $user->profile_id = $parent->id;
                $user->profile_type = 'parent';
                $user->password = bcrypt($request->password);
                $user->save();
                $user->syncRoleByCodeName('parent');
                DB::commit();
                
                return redirect(url('/school/parent'));
            }
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
        $admin  = Parents::find($id);
        $user = User::where('profile_id', $id)->where('profile_type', 'parent')->first();
        
        return view('backend.parent.add', ['admin' => $admin, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ParentRequest $request, $id)
    {
        DB::beginTransaction();
        try
        { 
            $parent = Parents::find($id);
            $parent->full_name = $request->full_name;
            $parent->address = $request->address;
            $parent->phone_number = $request->phone;
            $parent->contact_email = $request->contact_email;
            if ($request->hasFile("image"))              
            {  
                $media = Media::saveFile($request->file("image"));            
                $parent->avatar_id = $media->id;
            }
            $parent->updated_by = \Auth::user()->id;
            $parent->save();
            
            $user = User::where('profile_id', $id)->where('profile_type', 'parent')->first();
            $user->email = $request->email;
            $user->save();
            DB::commit();
            return redirect(url('school/parent'));
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
            $student = StudentParent::where('parent_id')->get();
            if ($student)
            {
                return redirect(url('school/parent'));
            }
            else
            {
                $parent = Parents::find($id)->delete();
                DB::commit();
                return redirect(url('school/parent'))->with('confirm' , trans('delete_success'));
            }
            
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

  
}


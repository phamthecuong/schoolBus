<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Requests\BackEnd\schoolAdminRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SchoolAdmin;
use App\Models\User;
use DB,Auth, Hash;

class SchoolAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($school_id)
    {
        return view('backend.school_admin.index',['school_id'=> $school_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($school_id)
    {
        return view('backend.school_admin.add',['school_id'=> $school_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(schoolAdminRequest $request, $school_id)
    {
        DB::beginTransaction();
        try
        {
            $admin = new SchoolAdmin;
            $admin->full_name = $request->name;
            $admin->school_id = $school_id;
            $admin->created_by = \Auth::user()->id;
            $admin->save();

            $user = new User;
            $user->profile_id = $admin->id;
            $user->email = $request->email;
            $user->profile_type = 'school';
            $user->password = \Hash::make($request->password);
            $user->save();
            $user->syncRoleByCodeName('school');
            DB::commit();
            return redirect(url("/admin/school/$school_id/admin"))->with(['flash_level'=>'success','flash_message'=>trans('school_admin.success_add')]);
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
    public function edit($school_id, $id)
    {   
        $admin = SchoolAdmin::find($id);
        $user = User::where('profile_id', $id)->where('profile_type','school')->first();
        return view('backend.school_admin.add',['school_id'=> $school_id,'admin'=>$admin, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(schoolAdminRequest $request, $school_id, $id)
    {   
        $admin =  SchoolAdmin::find($id);
        $admin->full_name = $request->name;
        $admin->updated_by = Auth::user()->id;
        $admin->save();

        $user = User::where('profile_id', $id)->where('profile_type','school')->first();
        $user->email = $request->email;
        // if ($request->has('password'))
        // {
        //     return 1;
            // if (\Hash::make($request->has('new_password')) == $user->password)
            // {
            //     $user->password = $request->new_password;
            // }
        // }
        $user->save();
        return redirect(url("/admin/school/$school_id/admin"))->with(['flash_level'=>'success','flash_message'=>trans('school_admin.success_edit')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($school_id , $id)
    {
        $admin =  SchoolAdmin::find($id)->delete();
        $user = User::where('profile_id', $id)->where('profile_type','school')->first()->delete();
        return redirect(url("/admin/school/$school_id/admin"))->with(['flash_level'=>'success','flash_message'=>trans('school_admin.success_delete')]);
    }

    public function getChangePassword($school_id, $id)
    {
        $admin = SchoolAdmin::find($id);
        return view('backend.school_admin.change-password', [
                'admin' => $admin,
                'school_id'=> $school_id
            ]);
    }

    public function postChangePassword(schoolAdminRequest $request, $school_id, $id)
    {
        DB::beginTransaction();
        try
        {
            $admin =  SchoolAdmin::find($id);
            $user = User::where('profile_id', $id)->where('profile_type', 'school')->first();
            $user->password = Hash::make($request->new_password);
            $user->save();
            $admin->updated_by = Auth::user()->id;
            $admin->save();
            DB::commit();
            // return redirect(url('admin/school/$school_id/admin'))->with('success', trans('validation.change_password_success'));
             return redirect(url("/admin/school/$school_id/admin"))->with(['flash_level'=>'success','flash_message'=>trans('school_admin.change_password_success')]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}

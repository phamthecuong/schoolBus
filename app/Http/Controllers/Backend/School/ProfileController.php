<?php

namespace App\Http\Controllers\Backend\School;

use App\Http\Requests\BackEnd\ProfileRequest;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session, Hash,Auth;
use DB;
//use App\Http\Requests\Backend\ChangePasswordRequest;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $user = User::find(Auth::user()->id);
        return view('backend.profile.index', ['user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request)
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $request, $id)
    {   
        $user = User::find($id);
        //save  to profile
        $model = $user->profile;
        if (isset($model->full_name))
        {
            $model->full_name = $request->name;
            if (isset($request->phone_number))
            {
                $model->phone_number = $request->phone_number;
            }
            $model->save();
        }
        $user->email = $request->email;
        if ($request->has('new_password'))
        {
            if (Hash::check($request->old_pass, $user->password))
            {
                $user->password = Hash::make($request->new_password);
                $user->save();
                DB::commit();
                return  redirect()->back()->with(['flash_level'=>'success','flash_message'=>trans('validate.change_profile_success')]);        
            }
            else
            {
                return  redirect()->back()->with(['flash_level'=>'danger','flash_message'=>trans('validate.change_profile_error')]);
            }
        }
        else
        {
            $user->save();
            DB::commit();
            return  redirect()->back()->with(['flash_level'=>'success','flash_message'=>trans('validate.change_profile_success')]);
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
        
    }

    public function changePassword(ProfileRequest $request, $id)
    {
        $user = User::find($id);
            if (Hash::check($request->old_password, $user->password))
            {
                $user->password = Hash::make($request->new_password);
                $user->save();
                DB::commit();
                return  redirect()->back()->with(['flash_level'=>'success','flash_message'=>trans('validate.change_profile_success')]);
            }
            else
            {
                return  redirect()->back()->with(['flash_level'=>'danger','flash_message'=>trans('validate.change_profile_error')]);
            }
        
        
    }
}

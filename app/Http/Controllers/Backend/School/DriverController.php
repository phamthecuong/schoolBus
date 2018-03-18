<?php

namespace App\Http\Controllers\Backend\School;

use App\Http\Requests\BackEnd\DriverRequest;
use App\Http\Requests\BackEnd\ChangePasswordRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Media;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Driver;
use App\Models\SchoolAdmin;
use App\Models\LBM_conversation_user;
use App\Models\LBM_conversation;
use DB,Auth,Hash;

class DriverController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.driver.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.driver.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DriverRequest $request)
    {
        DB::beginTransaction();
        try
        {   
            $driver = new Driver;              
            $driver->phone_number = $request->phone;
            $driver->full_name = $request->full_name;
            $driver->phone_number = $request->phone;
            $driver->created_by = \Auth::user()->id;
            if ($request->hasFile("image"))              
            {  
                $media = Media::saveFile($request->file("image"));            
                $driver->avatar_id = $media->id;
            }
            $driver->school_id = \Auth::user()->profile->school_id;
            $driver->save();

            $user = new User;
            $user->password = Hash::make($request->password);
            $user->profile_id = $driver->id;
            $user->profile_type = 'driver';
            $user->email = (string)$request->email;
            $user->save();
            //$user->syncRoleByCodeName('driver');
            $this->_addConversation($user->id);
            DB::commit();
            return redirect(url('/school/driver'))->with(['flash_level'=>'success','flash_message'=>trans('driver.success_add')]);
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
        $driver = Driver::find($id);
        $user = User::where('profile_id', $id)->where('profile_type', 'driver')->first();
        return view('backend.driver.add', ['driver' => $driver, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DriverRequest $request, $id)
    {
        DB::beginTransaction();
        try
        {   
            $driver =  Driver::find($id);              
            $driver->phone_number = $request->phone;
            $driver->full_name = $request->full_name;
            $driver->phone_number = $request->phone;
            $driver->updated_by = \Auth::user()->id;
            if ($request->hasFile("image"))              
            {  
                $media = Media::saveFile($request->file("image"));            
                $driver->avatar_id = $media->id;
            }
            $driver->school_id = \Auth::user()->profile->school_id;
            $driver->save();

            $user = User::where('profile_id', $id)->where('profile_type', 'driver')->first();
            if ($request->has('new_password'))
            {   
                if ($user->password == Hash::make($request->new_password))
                {
                    $user->password = $request->new_password; 
                }
            }
            $user->email = $request->email;
            $user->save();

            DB::commit();
            return redirect(url('/school/driver'))->with(['flash_level'=>'success','flash_message'=>trans('driver.success_edit')]);
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
            $trip = Driver::find($id)->trips;
            if (count($trip) > 0)
            {
                return redirect(url('school/driver'));
            }
            else
            {
                $driver = Driver::find($id);
                $driver->delete();
                User::where('profile_id', $id)->where('profile_type','driver')->first()->delete();
                DB::commit();
                return redirect(url('school/driver'))->with(['flash_level'=>'success','flash_message'=>trans('driver.success_delete')]);
            }
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }   

    }

    private function _addConversation($user_id)
    {
        $school_id = User::find($user_id)->profile->school_id;
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
            $conversation->created_by = $user_id;
            $conversation->save();
            $conversation->users()->sync([$user_id, $school_admin->id]);
        }
        else
        {
            return null;
        }
    }

    public function getChangePassword($id)
    {
        $driver = Driver::findOrFail($id);
        return view('backend.driver.change-password', ['driver' => $driver]);
    }

    public function postChangePassword(ChangePasswordRequest $request, $id)
    {
        DB::beginTransaction();
        try
        {
            $driver = Driver::findOrFail($id);
            $user = User::where('profile_id', $id)->where('profile_type', 'driver')->first();
            if (Hash::check($request->old_password, $user->password))
            {
                $user->password = Hash::make($request->new_password);
                $user->save();
                $driver->updated_by = \Auth::user()->id;
                $driver->save();
                DB::commit();
                return redirect(url('school/driver'))->with(['flash_level'=>'success','flash_message'=>trans('validation.change_password_success')]);
            }
            else
            {
                return redirect(url('/school/driver/'.$id . '/change_password'))->with([
                    'flash_level' => 'danger',
                    'flash_message' => trans('validate.change_password_error')
                ]);
            }
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}


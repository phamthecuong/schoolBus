<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Requests\BackEnd\AcountRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\BackEnd\ChangePasswordRequest;
use App\Models\User;
use DB,Auth,Hash;

class AcountController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function getChangePassword($id)
     {
        $user = User::find($id);
         return view('backend.acount.change-password', ['user' => $user]);
     }
     public function postChangePassword(ChangePasswordRequest $request, $id)
     {
         DB::beginTransaction();
         try
         {
             $user = User::find($id);
             if (Hash::check($request->old_password, $user->password))
             {
                 $user->password = Hash::make($request->new_password);
                 $user->updated_by = Auth::user()->id;
                 $user->save();
                 DB::commit();
                 return redirect(url('admin/account'))->with(['flash_level'=>'success','flash_message'=>trans('validation.change_password_success')]);
             }
             else
             {
                return redirect(url('/admin/account/change_password/'.$id))->with(['flash_level'=>'danger','flash_message'=>trans('validate.change_password_error')]);
             }
         } catch (\Exception $e) {
             DB::rollBack();
             dd($e->getMessage());
         }
     }

    public function index()
    {
        return view('backend.acount.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.acount.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AcountRequest $request)
    {
        DB::beginTransaction();
        try
        {   
            $user = new User;
            $user->profile_id = NULL;
            $user->profile_type = 'admin';
            $user->password = Hash::make($request->password);
            $user->email = $request->email;
            $user->created_by = Auth::user()->id;
            $user->save();
            $user->syncRoleByCodeName('admin');
            DB::commit();
            return redirect(url('/admin/account'));
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
        $user = User::find($id);
        return view('backend.acount.add', [ 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AcountRequest $request, $id)
    {
        DB::beginTransaction();
        try
        {   
            $user =  User::find($id);
            if ($request->has('email'))
            {
                $user->email = $request->email;
                $user->updated_by = Auth::user()->id;
            }
            $user->save();
            DB::commit();
            return redirect(url('/admin/account'));
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
	        User::find($id)->delete();
	        DB::commit();
	        return redirect(url('admin/account'));
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }   

    }
}

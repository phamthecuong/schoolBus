<?php

namespace App\Http\Controllers\Backend\School;

use App\Http\Requests\BackEnd\ParentPasswordRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
class ParentPassController extends Controller
{
    public function index($parent_id)
    {
    	return view('backend.parent.password', ['parent_id'=> $parent_id]);
    }

    public function store(ParentPasswordRequest $request,$parent_id)
    {   
        $user = User::where('profile_id', $parent_id)->where('profile_type', 'parent')->first();
        if(Hash::check($request->old_password, $user->password))
        {           
            $user->password = Hash::make($request->new_password);;
            $user->save(); 
            return redirect(url('school/parent'))->with(['flash_level' => 'success', 'flash_message' => trans('validate.change_password_success')]);
        }
        else
        {    
            return redirect()->back()->with(['flash_level' => 'danger', 'flash_message' => trans('validate.Please_enter_correct_current_password')]);
        }
    	
    }
}

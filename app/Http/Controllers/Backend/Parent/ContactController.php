<?php

namespace App\Http\Controllers\Backend\Parent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BackEnd\ContactRequest;
use App\Models\ContactMessage;
use Auth;
use DB;
use App\Models\Parents;
use App\Models\School;
class ContactController extends Controller
{
    public function index()
    {

    	return view('parent.contact.index');
    }

    public function add(ContactRequest $request)
    {
    	DB::beginTransaction();
    	try
    	{
    		$contact_message = new ContactMessage();
    		$contact_message->title = $request->title;
    		$contact_message->message = $request->message;
    		$contact_message->status = 0;
    		//$contact_message->created_by = Auth::user()->profile_id;
            $contact_message->school_id = $request->school;
    		$contact_message->save();
    		DB::commit();
    		return redirect(url('parent/contact'))->with(['flash_level'=>'success','flash_message'=>trans('contact.success_add')]);
    	}
    	catch (\Exception $e)
    	{
    		DB::rollBack();
    		dd($e->getMessage());
    	}	
    }
}

<?php

namespace App\Http\Controllers\Backend\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use DB;
class ContactController extends Controller
{
    public function index()
    {
    	return view('backend.contact.index');
    }

    public function read($id)
    {
    	DB::beginTransaction();
    	try
    	{
    		$contact_message = ContactMessage::findOrFail($id);
    		$contact_message->status = 1;
            $contact_message->updated_by = \Auth::user()->id;
    		$contact_message->save();
    		DB::commit();
    		return redirect(url('school/contact'))->with(['flash_level'=>'success','flash_message'=>trans('contact.success_read')]);
    	}
    	catch (\Exception $e)
    	{
    		DB::rollBack();
    		dd($e->getMessage());
    	}
    }
}

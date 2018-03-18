<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Yajra\Datatables\Facades\Datatables;
use Auth;

class ContactController extends Controller
{
    public function index()
    {
    	$school_id = Auth::user()->profile->school_id;
    	$contact_message = ContactMessage::where('school_id',$school_id)->get();
    	return Datatables::of($contact_message)->make(true);
    }
}

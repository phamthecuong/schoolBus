<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use Auth;

class ClassTransferController extends Controller
{
    public function findClass(Request $request)
    {
    	$school_id = Auth::user()->profile->school_id;
    	$class = Classes::where('year', $request->year)->where('school_id', $school_id)->get();
    	return response()->json($class);
    }
}

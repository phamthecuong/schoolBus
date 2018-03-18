<?php

namespace App\Http\Controllers\Backend\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LBM_conversation;
use App\Models\User;
use App\Models\Parents;
use App\Models\Driver;
use App\Models\SchoolAdmin;
use Auth;

class ContactConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isParent())
        {
            $students = Auth::user()->profile->students;
            $school_admin = [];
            $school_ids = [];
            foreach ($students as $student) {
                $school_id = $student->classes[0]->school_id;
                $admin = SchoolAdmin::where('school_id', $school_id)->with('users', 'school')->first();
                $school_admin[] = $admin;
                $school_ids[] = $school_id;
            }
            $school_admins = array_unique($school_admin);
            $drivers = Driver::whereIn('school_id', $school_ids)->with('users')->get();
            return view("libressltd.lbmessenger.conversation.parent", [
                "school_admins" => $school_admins,
                'drivers' => $drivers
            ]);
        }
        elseif (Auth::user()->isSchoolAdmin())
        {
            $school_id = Auth::user()->profile->school_id;
            $parents = Parents::whereHas('students', function ($query) use ($school_id) {
                $query->whereHas('classes', function ($query) use ($school_id) {
                    $query->where('school_id', $school_id);
                });
            })->with('users')->get();
            $drivers = Driver::where('school_id', $school_id)->with('users')->get();
            return view("libressltd.lbmessenger.conversation.admin", [
                "parents" => $parents,
                'drivers' => $drivers
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LBM_conversation::find($id)->delete();
        return $id;
    }
}

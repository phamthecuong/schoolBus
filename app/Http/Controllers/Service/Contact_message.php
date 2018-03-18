<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Requests\Service\ContactMessageRequest;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\School;
use App\Models\Parents;
use Auth;

class Contact_message extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parents = Parents::with('students.classes')->findOrFail(Auth::user()->profile_id);
        foreach ($parents->students as $student)
        {
            $class_id[] = $student->classes[0]->id;
        }
        $schools = School::with('classes')->whereHas('classes', function ($query) use ($class_id) {
            $query->whereIn('classes.id', $class_id);
        })->get();
        $data = [];
        foreach ($schools as $school)
        {
            $data[] = ['name' => $school->name, 'id' => $school->id];
        }
        return $data;
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
    public function store(ContactMessageRequest $request)
    {
        $contact_message = new ContactMessage();
        $contact_message->title = $request->title;
        $contact_message->message = $request->message;
        $contact_message->status = 0;
        $contact_message->created_by = Auth::user()->id;
        $contact_message->school_id = $request->school_id;
        $contact_message->save();

        return response([
            'description' => 'success'
        ]);
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
        //
    }
}

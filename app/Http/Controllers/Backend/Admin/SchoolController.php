<?php

namespace App\Http\Controllers\Backend\Admin;
use App\Http\Requests\BackEnd\SchoolRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Classes;
use App\Models\SchoolAdmin;
use App\Models\Teacher;
use App\Models\Trip;
use DB, Auth;

class SchoolController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!\Auth::user()->hasRole('superadmin') && !\Auth::user()->hasRole('admin'))
            {
                abort(500, "Permission denied");   
            }
            return $next($request);
        });  
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.school.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('backend.school.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SchoolRequest $request)
    {
        DB::beginTransaction();
        try
        {   
            $school = new School;
            $school->name = $request->name;
            $school->phone_numbers = $request->phone;
            $school->address = $request->address;
            $school->created_by = Auth::user()->id;
            $school->save();
            DB::commit();
            return redirect(url('/admin/school'))->with('confirm',trans('alert.create_success'));
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
      	$school = School::find($id);
        return view('backend.school.add', ['school' => $school]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SchoolRequest $request, $id)
    {
        DB::beginTransaction();
        try
        {   
            $school = School::find($id);
            $school->name = $request->name;
            $school->phone_numbers = $request->phone;
            $school->address = $request->address;
            $school->updated_by = Auth::user()->id;
            $school->save();
            DB::commit();
            return redirect(url('/admin/school'))->with('confirm',trans('alert.edit_success'));
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
            $class = Classes::where('school_id', $id)->first();
            $school_admin = SchoolAdmin::where('school_id',$id)->first();
            $techer = Teacher::where('school_id', $id)->first();
            $trip = Trip::where('school_id', $id)->first();
            if (isset($class) || isset($school_admin) || isset($techer)|| isset($trip))
            {
                return trans('school.school_has_class_you_have_to_clear');
            }
            else
            {
                School::find($id)->delete();
                DB::commit();
                return redirect(url('admin/school'))->with('confirm', trans('alert.delete_success'));
            }
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }   
    }
}

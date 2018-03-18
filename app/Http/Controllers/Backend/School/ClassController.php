<?php

namespace App\Http\Controllers\Backend\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Media;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Driver;
use App\Models\School;
use DB,Auth;
use App\Http\Requests\BackEnd\ClassRequest;

class ClassController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view('backend.class.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('backend.class.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassRequest $request)
    { 
        DB::beginTransaction();
        try
        {   
            $class = new Classes; 
            $class->name = $request->name;  
            $class->teacher_id = $request->teacher;
            $class->school_id = Auth::user()->profile->school_id;
            $class->year = $request->year;
            $class->created_by = \Auth::user()->id;
            $class->save();
            DB::commit();

            return redirect(url('/school/class'))->with(['flash_level'=>'success','flash_message'=>trans('class.success_add')]);
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
        $class = Classes::find($id);
        return view('backend.class.add',['class'=> $class]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClassRequest $request, $id)
    {
        DB::beginTransaction();
        try
        {   
            $class = Classes::find($id);
            $class->name = $request->name;
            $class->teacher_id = $request->teacher;
            $class->year = $request->year;
            $class->updated_by = \Auth::user()->id;
            $class->save();

            DB::commit();
            return redirect(url('/school/class'))->with(['flash_level'=>'success','flash_message'=>trans('class.success_edit')]);
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
            $student = Classes::find($id)->student;
            if (count($student) >0)
            {
                return redirect(url('school/class'));
            }
            else
            {
                Classes::find($id)->delete();
                DB::commit();
                return redirect(url('school/class'))->with('confirm',trans('alert.delete_class_success'));
            }
           
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }   

    }
}

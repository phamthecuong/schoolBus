<?php

namespace App\Http\Controllers\Backend\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Models\Student;
use App\Http\Requests\BackEnd\ClassTransferRequest;

class ClassTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.class_transfer.index');
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
    public function store(ClassTransferRequest $request)
    {
        DB::beginTransaction();
        try
        {
            if (isset($request->data) && !empty($request->data))
            {
                foreach($request->data as $key => $value)
                {
                    if ($value == TRUE)
                    {
                        $student = Student::findOrFail($key);
                        $student->classes()->sync($request->new_class);
                        $student->save();
                    }              
                }          
            }
           
            DB::commit();
            return redirect(url('/school/class_transfer'))->with([
                'flash_level'=>'success',
                'flash_message'=>trans('student.success_class_transfer')
            ]);
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

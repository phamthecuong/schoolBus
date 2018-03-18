<?php

namespace App\Http\Controllers\Backend\School;

use App\Http\Requests\BackEnd\ParentStudentRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\SchoolAdmin;
use App\Models\LBM_conversation_user;
use App\Models\LBM_conversation;
use Auth;
class ParentStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.parent_student.index');
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
    public function store(ParentStudentRequest $request)
    {
        \DB::beginTransaction();
        try
        {
            $student = Student::where('code', $request->code)->first();
            if (isset($student->id))
            {   
                $parent_id = Auth::user()->profile->id;
                $p_s =  StudentParent::where('student_id',$student->id)->where('parent_id', $parent_id)->first();
                if (isset($p_s->id))
                {
                    return redirect()->back()->with('confirm', trans('alert.exist'));
                }
                else
                {
                    $p_s = new StudentParent;
                    $p_s->student_id = $student->id;
                    $p_s->parent_id =  $parent_id;
                    //$p_s->created_by = $parent_id;
                    $p_s->created_by = Auth::user()->id;
                    $p_s->save();
                    $this->_addConversation(Auth::user()->id, $student->id);
                    \DB::commit();
                    return redirect()->back()->with(['flash_level'=>'success','flash_message'=>trans('alert.add_new_student_success')]);
                }
                
            }
            else
            {
                return redirect()->back()->with(['flash_level'=>'danger','flash_message'=>trans('alert.code_not_exist')]);
            }
        }
        catch (Exception $e)
        {
            \DB::rollBack();
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

    private function _addConversation($user_id, $student_id)
    {
        $school_id = Student::find($student_id)->classes[0]->school_id;
        $school_admin = SchoolAdmin::where('school_id', $school_id)->first()->users[0];
        $check = false;
        $data = LBM_conversation_user::select('conversation_id', \DB::raw('COUNT(*) as total_conversation'))
            ->whereIn('user_id', [$user_id, $school_admin->id])
            ->groupBy('conversation_id')->get();
        if (count($data) > 0)
        {
            foreach ($data as $data) 
            {
                if ($data->total_conversation == 2)
                {
                    $check = true;
                    break;
                }
            }
        }
        if (!$check) 
        {
            $conversation = new LBM_conversation();
            $conversation->created_by = $user_id;
            $conversation->save();
            $conversation->users()->sync([$user_id, $school_admin->id]);
        }
        else
        {
            return null;
        }
    }
}

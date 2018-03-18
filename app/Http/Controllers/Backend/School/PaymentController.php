<?php

namespace App\Http\Controllers\Backend\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Payment;
use App\Models\Classes;
use App\Models\Student;
use App\Models\SchoolAdmin;
use App\Models\User;
use DB;
use App\Http\Requests\BackEnd\PaymentRequest;
use Carbon\Carbon;
use App\Models\Departure;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);
        if ($user->isSchoolAdmin())
        {
            
            $user_id = Auth::user()->profile_id;
            $school_admin = SchoolAdmin::findOrFail($user_id);
            $id = $school_admin->school_id;
            $payments = Payment::all();
            
            foreach ($payments as $payment)
            {    
                //$student = Student::findOrFail($payment->student_id)->classes()->get();

                $student = Student::join('class_students','students.id','=','student_id')->where('student_id',$payment->student_id)->get();
                foreach ($student as $item)
                {
                    $class = Classes::findOrFail($item->class_id);
                    if ($class->school_id == $id)
                    {
                        $data[] = ['name'=>$item->full_name,'id'=>$payment->id,'description'=>$payment->description,'amount'=>$payment->amount,'month'=>$payment->month,'year'=>$payment->year,'payment_date'=>$payment->payment_date]; 
                    }
                }
            }
            if (isset($data))
            {
                return view('backend.payment.index',['data'=>$data]);
            }
            else return view('backend.payment.index');
            
        } 
        else 
        {
            return view('backend.payment.index');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.payment.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $payment = new Payment();
            $payment->student_id = $request->student;
            $payment->description = $request->description;
            $payment->amount = $request->amount;
            $payment->month = $request->month;
            $payment->year = $request->year;
            $payment->created_by = Auth::user()->id;
            //$payment->updated_by = Auth::user()->profile_id;
            $payment->save();
            DB::commit();
            return redirect(url('school/payment'))->with(['flash_level'=>'success','flash_message'=>trans('general.success_add')]);        
        }
       catch (\Exception $e)
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
        $payment = Payment::findOrFail($id);
        //$student = Student::findOrFail($payment->student_id);
        $class_student = Classes::with('student')->whereHas('student', function ($query) use ($payment) {
            $query->where('students.id',$payment->student_id);
        })->take(1)->get();
        //$class_student = ClassStudent::where('student_id',$payment->student_id)->take(1)->get();
        //$student = Student::where('id',$payment->student_id)->get();
        foreach($class_student as $item)
        {
            $class[] = ['id'=>$item->id];
        }
        return view('backend.payment.add',['payment'=>$payment,'class'=>$class]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentRequest $request, $id)
    {
        DB::beginTransaction();
        try
        {
            $payment = Payment::findOrFail($id);
            $payment->student_id = $request->student;
            $payment->description = $request->description;
            $payment->amount = $request->amount;
            $payment->month = $request->month;
            $payment->year = $request->year;
            $payment->updated_by = Auth::user()->id;
            $payment->save();
            DB::commit();
            return redirect(url('school/payment'))->with(['flash_level'=>'success','flash_message'=>trans('general.success_edit')]);        
        }
       catch (\Exception $e)
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
            $payment = Payment::findOrFail($id);
            $payment->delete();
            DB::commit();
            return redirect(url('school/payment'))->with(['flash_level'=>'success','flash_message'=>trans('general.success_delete')]);        
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }   
    }

    public function findStudent(Request $request)
    {    
        $data = Student::join('class_students','students.id','=','student_id')->where('class_id',$request->id)->get();
        return response()->json($data);
    }

    public function pay($id)
    {
        DB::beginTransaction();
        try
        {
            $payment = Payment::findOrFail($id);
            $payment->payment_date = Carbon::now();
            $payment->updated_by = Auth::user()->id;
            $payment->save(); 
            DB::commit();
            return redirect(url('school/payment'))->with(['flash_level'=>'success','flash_message'=>trans('general.success_payment')]);        
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}

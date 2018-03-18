<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentMail;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Parents;
class PaymentEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email payment to parents monthly in 15th';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $month = date('m');
        $year = date('Y');
        $payments = Payment::all();
        foreach ($payments as $payment)
        {
            if ($payment->month == $month && $payment->year == $year && !isset($payment->payment_date))
            {
                $student = Student::join('student_parents','students.id','=','student_id')->where('student_id',$payment->student_id)->get();
                foreach($student as $item)
                {
                    $parent = Parents::findOrFail($item->student_id);
                    Mail::to($parent->contact_email)->send(new PaymentMail($parent->full_name, $item->full_name, $payment->amount, $month, $year));
                }
            } 
        } 
    }
}

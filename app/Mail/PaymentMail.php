<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentMail extends Mailable
{
     use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($parent_name, $student_name, $amount, $month, $year)
    {
        $this->parent_name = $parent_name;
        $this->student_name = $student_name;
        $this->amount = $amount;
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.payment')
            ->with([
                'parent_name' => $this->parent_name,
                'student_name' => $this->student_name,
                'amount' => $this->amount,
                'month' => $this->month,
                'year' => $this->year
            ]);
    }
}

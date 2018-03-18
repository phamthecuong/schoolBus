<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->hasRole('superadmin') || \Auth::user()->hasRole('admin'))
        {
            return redirect('admin/school');
        }
        else if (\Auth::user()->hasRole('school'))
        {
            return redirect('school/report');   
        }
        else if (\Auth::user()->hasRole('parent'))
        {
            return redirect('parent/payment');
        }
        // return view('backend.parent.index');
    }
}

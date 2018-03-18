<?php

namespace LIBRESSLtd\LBForm\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cookie;
use Auth;

class LBFormController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }
   
    public function getSetLang($lang)
    {
        return redirect()->back()->withCookie(Cookie::make("locale", $lang));
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url("/"));
    }
}

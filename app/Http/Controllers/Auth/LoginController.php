<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    // public function authenticate()
    // {
    //     dd(1);
    //     if (
    //         \Auth::attempt(['email' => $_POST['email'], 'password' => $_POST['password'], 'freeze' => 0, 'activate' => 1]) ||
    //         \Auth::attempt(['name' => $_POST['email'], 'password' => $_POST['password'], 'freeze' => 0, 'activate' => 1])
    //     )
    //     {
    //         return redirect()->intended($this->redirectTo);
    //     }
    //     \Auth::logout();

    //    $new_message = new \Illuminate\Support\MessageBag;
    //     $new_message->add('email', trans('auth.Username or password is wrong or account is not activated. Please check your mail box and confirm your email verification if you didn\'t do it.'));
    //     return redirect('/login')
    //         // ->withInput([$_POST['email']])
    //         ->withErrors($new_message);
    // }

    public function login(Request $request)
    {
        if (\Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            return redirect('/home');
        }
        else
        {
            // check phone number
            $parent = \App\Models\Parents::where('phone_number', $request->email)->get();
            if ($parent->count() == 1)
            {
                if (\Hash::check($request->password, $parent->first()->users->first()->password))
                {
                    \Auth::loginUsingId($parent->first()->users->first()->id, $request->remember);
                    return redirect('/home');
                }
            }
        }

        return redirect('/login')->withInput([$request->email])
            ->withErrors([
                'error' => trans('auth.failed'),
            ]);
    }
    
}

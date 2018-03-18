<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use Socialite;
use App\Models\Media;

class SocialAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback()
    {   
        
        $user = Socialite::driver('facebook')->stateless()->user();
        $id = $user->id;
        $u = User::where('facebook_id', $id)->first();
        if ($u)
        {
            Auth::login($u,true);
            //return redirect('homepage');
            return redirect('home');
        }
        else 
        {
            $facebook_id = $user->getId();
            $facebook_name = $user->getName();
            $facebook_email = $user->getEmail();
            $facebook_avatar = $user->avatar_original;
            // echo "<pre>";
            // print_r($facebook_avatar);
            // echo '</pre>';
            //$user->avatar()->associate(Media::download_file($fbuser->avatar_original));
            return redirect('register')->with([  
                'facebook_id' => $facebook_id,
                'facebook_name' => $facebook_name,
                'facebook_email' => $facebook_email,
                'facebook_avatar' => $facebook_avatar
            ]);
        }
    }

    // public function redirectgg()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // public function callbackgg()
    // {
    //     echo 1;
    //     // when facebook  callus a with token
        
    //     $user = Socialite::driver('google')->user();
    //     $id = $user->id;
    //     $u = User::where('provider_id',$id)->where('provider','google')->get()->first();
    //     if ($u)
    //     {
    //         Auth::login($u);
    //         return redirect()->route('home');
    //     }
    //     else 
    //     {
    //         $dd = new User;
    //         $dd->name = $user->name;
    //         $dd->email=$user->email;
    //         $dd->provider="google";
    //         $dd->provider_id = $id;
    //         $dd->save();
    //         Auth::login($dd);
    //         return redirect()->route('home');
    //     }
    // }

    // public function redirecttwitter()
    // {
    //     return Socialite::driver('twitter')->redirect();
    // }
    
        
    // public function callbacktwitter()
    // {
    //     // when facebook call us a with token
    //     $user = Socialite::driver('twitter')->user();
    //     $id = $user->id;
    //     $u = User::where('provider_id',$id)->where('provider','twitter')->get()->first();
    //     // dd($bb);
    //     if ($u){
    //         Auth::login($u);
    //         return redirect()->route('home');
    //         // return 1;
    //     }
    //     else {
    //         $dd = new User;
    //         $dd->name = $user->name;
    //         $dd->provider="twitter";
    //         $dd->provider_id = $id;
    //         $dd->save();
    //         Auth::login($dd);
    //         return redirect()->route('home');
    //     }
        
    // }



}

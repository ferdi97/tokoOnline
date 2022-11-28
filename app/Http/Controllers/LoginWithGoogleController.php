<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;

class LoginWithGoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try{
            $googleUser = Socialite::driver('google')->stateless()->user();
            $User       = User::where('email', $googleUser->getEmail())->first();

            if ( $User == null ) {
                $User = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password'  => bcrypt('123123')
                ]);
            }

            auth()->login($User, true);

            return redirect()->route('dashboard');
            // $user = Socialite::driver('google')->user();
            // $finduser = User::where('email', $user->getEmail())->first();
            // if($finduser ==null){
            //     Auth::login($finduser);
            //     return redirect()->intended('dashboard');
            // }else{
            //     $newUser = User::create([
            //             'name' => $user->name,
            //             'email' => $user->email,
            //             'password' => bcrypt('123456dummy')
            //     ]);
            //     Auth::login($newUser);
            //     return redirect()->intended('dashboard');
            // }
        }catch (Exception $e){
            dd($e->getMessage());
        }
    }
}

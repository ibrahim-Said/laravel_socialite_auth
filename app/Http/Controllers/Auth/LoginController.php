<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use Illuminate\Support\Facades\Hash;
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * Redirect the user to the facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $usersocialite = Socialite::driver('facebook')->user();
        //check if user existe
        $user =User::where('email',$usersocialite->email)->first();
        if($user){
            if(Auth::loginUsingId($user->id)){
                return redirect('/');
            }

        }else{
            $usersignup=User::create([
                'name' => $usersocialite->name,
                'email' => $usersocialite->email,
                'password' => Hash::make('1236547@@mm'),
            ]);
            if($usersignup){
                if(Auth::loginUsingId($user->id)){
                    return redirect('/');
                }
            }

        }
    }
}

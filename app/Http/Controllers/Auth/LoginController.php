<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        if (Auth::check()){
            $this->redirectTo();
        }
    }

    protected function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['online'] = false;
        if(Auth::attempt($credentials)){
            // $user = Auth::user();
            // $user->online = true;
            // $user->save();
            return $this->redirectTo();
        } else {
            return redirect()->intended('/login');
        }
    }
    
    protected function logout(Request $request)
    {
        // $user = Auth::user();
        // $user->online = false;
        // $user->save();

        $request->session()->invalidate();
        Auth::logout();
        return redirect('/');
    }
    
    protected function redirectTo()
    {
        $room = Auth::user()->room()->first();
        $userLevel = Auth::user()->userBiodata()->first()->level;
        
        if($userLevel == 'admin'){
            return redirect('home/');
        } else if ($userLevel == 'rm'){
            return redirect('room/' . $room->url);
        }
    }

}

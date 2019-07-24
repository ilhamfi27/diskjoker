<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
    }
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['online'] = false;
        $this->validator($request);
        if(Auth::attempt($credentials)){
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
        if($room != NULL){
            if($userLevel == 'admin'){
                return redirect('home/');
            } else if ($userLevel == 'rm'){
                return redirect('room/' . $room->url);
            }
        } else {
            return redirect('room/create');
        }
    }

}

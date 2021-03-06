<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserBiodata;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\FileUpload;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use FileUpload;
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'avatar' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function userCreate(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function biodataCreate(array $data)
    {
        return UserBiodata::create([
            'nickname' => $data['nickname'],
            'avatar' => $data['avatar'],
            'user_id' => $data['user_id'],
            'level' => "rm",
        ]);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    protected function register(Request $request)
    {
        $validate_form = $this->validator($request);
        if($validate_form->fails()){
            return redirect()->back()
                             ->withErrors($validate_form)
                             ->withInput();
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];
        $user = $this->userCreate($userData);

        $userBiodata = [
            'nickname' => $request->nickname,
            'avatar' => $this->saveFiles($request->avatar, 'profile_photos'),
            'user_id' => $user->id,
        ];
        $this->biodataCreate($userBiodata);
        $this->guard()->login($user);

        return redirect(route('room.create'));
    }
}

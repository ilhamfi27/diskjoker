<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Controllers\Traits\FileUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\UserBiodata;

class UserProfileController extends Controller
{
    use FileUpload {
        saveFiles as protected saveFile;
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['edit']);
    }

    public function edit($id)
    {
        $user = User::find($id);
        $biodata = $user->userBiodata()->first();
        $data = [
            'user' => $user,
            'biodata' => $biodata
        ];
        return view('profile.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $biodata = UserBiodata::where('user_id', $id)->first();

        $this->validate($request,[
            'name'                      => 'required|min:3',
            'nickname'                  => 'required|min:3',
            'email'                     => 'required|string|email|max:255|unique:users,email,'.$id,
            'password'                  => 'confirmed:password_confirmation',
            'old_password_confirmation' => 'required',
        ]);
        
        $file = $request->file('avatar');
        $fileName = $file != NULL ? 
                        $this->saveFiles($file, 'profile_photos') : $biodata->avatar;

        echo "hai0";
        
        if(Hash::check($request->old_password_confirmation, $user->password)){
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->password = $request->password != NULL ? $request->password : $user->password;
            $user->save();

            $biodata->avatar = $fileName;
            $biodata->nickname = $request->nickname;
            $biodata->save();
            echo "hai1";
            return redirect(route('profile.edit', $id));
        } else {
            echo "hai2";
            return redirect(route('profile.edit', $id))
                     ->with(['old_password_confirmation_error' => 'Old Password Confirmation Doesn\'t Match The Record!']);
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $room = \App\Room::where('user_id', Auth::id())->first();
            $userLevel = \App\UserBiodata::where('user_id', Auth::id())->first()->level;
            if($userLevel == 'admin'){
                return redirect('home/');
            } else if ($userLevel == 'rm'){
                if($room != NULL){
                    return redirect('room/' . $room->url);
                } else {
                    return redirect('room/create');
                }
            }
        }

        return $next($request);
    }
}

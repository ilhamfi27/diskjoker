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

        return $next($request);
    }
}

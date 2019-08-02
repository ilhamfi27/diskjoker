<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class AlreadyHasRoom
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
        $room = Auth::user()->room()->first();
        if ($room != NULL) {
            return redirect(route('room.show', $room->url));
        }
        return $next($request);
    }
}

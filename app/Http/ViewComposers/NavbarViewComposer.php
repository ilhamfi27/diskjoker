<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Auth;

class NavbarViewComposer
{
    public function compose(View $view)
    {
        $view->with('userRoomUrl', $this->getUserRoom());
        $view->with('userData', $this->getUserData());
    }

    private function getUserRoom()
    {
        if(Auth::user()){
            $userRoom = Auth::user()->room()->first();
            return $userRoom != NULL ? $userRoom->url : NULL ;
        }
        return NULL;
    }

    private function getUserData()
    {
        return Auth::user() != NULL ? Auth::user() : NULL ;
    }
}


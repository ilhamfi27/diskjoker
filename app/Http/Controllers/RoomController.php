<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use \App\Room;
use \App\SongRequest;

class RoomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['create','edit']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function create()
    {
        return view('room.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'                      => 'required|min:5',
            'url'                       => 'required|min:3|unique:rooms|alpha_dash',
            'max_song_per_user'         => 'required|numeric',
            'user_max_song_limit_time'  => 'required|numeric',
            'max_song_per_guest'        => 'required|numeric',
            'guest_max_song_limit_time' => 'required|numeric',
            'max_song_duration'         => 'required|numeric',
        ]);
        $userId = Auth::id();
        $room = new Room;
        $room->name = $request->name;
        $room->url = $request->url;
        $room->user_id = $userId;
        $room->active = true;
        $room->max_song_per_user = $request->max_song_per_user;
        $room->max_song_per_guest = $request->max_song_per_guest;
        $room->user_max_song_limit_time = $request->user_max_song_limit_time;
        $room->guest_max_song_limit_time = $request->guest_max_song_limit_time;
        $room->max_song_duration = $request->max_song_duration;
        $room->save();
        
        return redirect(route('room.show', $room->url));
    }

    public function show($url)
    {
        $room = Room::findByUrl($url)->firstOrFail();
        $songRequests = SongRequest::roomSongRequests($room->id);
        $data = [
            'room' => $room,
            'songRequests' => $songRequests,
        ];
        // print_r($data);
        return view('room.show')->with($data);
    }

    public function edit($url)
    {
        $room = Room::findByUrl($url)->firstOrFail();
        $data = [
            'room' => $room,
        ];
        if($user = Auth::user()){
            if($user->room()->first()->id != $room->id){
                return redirect()->back();
            }
            return view('room.edit')->with($data);
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'                      => 'required|min:5',
            'url'                       => 'required|min:3|unique:rooms,url,' . $id . '|alpha_dash',
            'max_song_per_user'         => 'required|numeric',
            'user_max_song_limit_time'  => 'required|numeric',
            'max_song_per_guest'        => 'required|numeric',
            'guest_max_song_limit_time' => 'required|numeric',
            'max_song_duration'         => 'required|numeric',
        ]);
        $room = Room::find($id);
        $room->name                         = $request->name;
        $room->url                          = $request->url;
        $room->max_song_per_user            = $request->max_song_per_user;
        $room->user_max_song_limit_time     = $request->user_max_song_limit_time;
        $room->max_song_per_guest           = $request->max_song_per_guest;
        $room->guest_max_song_limit_time    = $request->guest_max_song_limit_time;
        $room->max_song_duration            = $request->max_song_duration;
        $room->save();

        return redirect(route('room.show', $room->url));
    }
}

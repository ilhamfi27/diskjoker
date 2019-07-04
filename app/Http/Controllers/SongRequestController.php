<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Guest;
use App\Room;
use App\SongRequest;

class SongRequestController extends Controller
{
    private function validator(array $data)
    {
        return Validator::make($data, [
            'url' => ['required'],
        ]);
    }

    function store(Request $request)
    {
        $user = Auth::user();
        $room = Room::find($request->room_id);
        $identification = $user ? $user->id : $request->ip();
        $user_type = $user ? 'member' : 'guest';
        $maxRequest = $user ? $room->max_song_per_user : $room->max_song_per_guest;

        $validator = $this->validator([
            'url' => $request->url
        ]);

        $totalRequest = SongRequest::countUserRequests($room->id, $user_type, $identification);
        $lastQueue = SongRequest::lastSongQueue($room->id);

        if($validator->fails()){
            return response()->json([
                'warning' => true,
                'message' => $validator->errors()->first('url')
            ]);
        }

        if ($totalRequest >= $maxRequest) {
            return response()->json([
                'error' => true,
                'message' => 'Maximum request song exceeded for today!'
            ]);
        } else {
            $songRequest = [
                'url' => $request->url,
                'room_id' => $request->room_id,
                'queue' => $lastQueue == null ?
                                1 : $lastQueue + 1,
                'user_type' => $user_type,
                'user_id' => $user == null ?
                            null : $user->id,
                'ip_address' => $user == null ?
                            $request->ip() : null,
            ];
            $this->insertSongRequest($songRequest);
        }
        $songRequests = SongRequest::roomSongRequests($request->room_id);
        return response()->json([
            'error' => false,
            'message' => 'Song added!',
            'song_request_data' => $songRequests
        ]);
    }

    private function insertSongRequest($data)
    {
        $songRequest = new SongRequest;
        $songRequest->url = $data['url'];
        $songRequest->room_id = $data['room_id'];
        $songRequest->queue =  $data['queue'];
        $songRequest->status = 'queue';
        $songRequest->user_type = $data['user_type'];
        $songRequest->user_id =  $data['user_id'];
        $songRequest->ip_address =  $data['ip_address'];
        return $songRequest->save();
    }
}

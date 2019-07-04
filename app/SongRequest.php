<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SongRequest extends Model
{

    protected $fillable = [
        'video_id', 'user_id', 'user_type', 'queue', 'status', 'title', 'thumbnail_df'
    ];

    function room()
    {
        return $this->belongsTo('App\Room');
    }

    public static function countUserRequests($room_id, $user_type, $identification)
    {
        $songRequest = DB::table('song_requests')
                    ->where('room_id', $room_id)
                    ->whereDay('created_at', date('d'))
                    ->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'));
        if($user_type === 'member'){
            $totalRequest = $songRequest->whereNotNull('user_id')
                                 ->where('user_id', $identification)
                                 ->count();
            return $totalRequest;
        }
        $totalRequest = $songRequest->whereNull('user_id')
                             ->where('ip_address', $identification)
                             ->count();
        return $totalRequest;
    }

    public static function lastSongQueue($room_id)
    {
        $songRequest = DB::table('song_requests')
                        ->where('room_id', $room_id)
                        ->whereDay('created_at', date('d'))
                        ->whereMonth('created_at', date('m'))
                        ->whereYear('created_at', date('Y'))
                        ->orderBy('queue', 'desc')
                        ->first();
        if ($songRequest !== null) {
            return $songRequest->queue;
        }
        return null;
    }

    public static function roomSongRequests($room_id, $status='queue')
    {
        $songRequest = DB::table('song_requests')
                        ->where('room_id', $room_id)
                        ->where('status', $status)
                        ->whereDay('created_at', date('d'))
                        ->whereMonth('created_at', date('m'))
                        ->whereYear('created_at', date('Y'))
                        ->orderBy('queue', 'asc')
                        ->get();
        if ($songRequest !== null) {
            return $songRequest;
        }
        return null;
    }
}

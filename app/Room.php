<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'active',
        'url',
        'max_song_per_user',
        'max_song_duration',
    ];

    public static function findByUrl($url)
    {
        $room = Room::where('url', $url);
        return $room;
    }

    function user()
    {
        return $this->belongsTo('App\User');
    }

    function songRequests()
    {
        return $this->hasMany('App\SongRequest');
    }
}

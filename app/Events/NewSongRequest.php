<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\SongRequest;

class NewSongRequest implements ShouldBroadcast 
{
    use SerializesModels;

    protected $songRequest;

    public function __construct(SongRequest $songRequest)
    {
        $this->songRequest = $songRequest;
    }

    public function broadcastOn()
    {
        return ['song-added'];
    }
}

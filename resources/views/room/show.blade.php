@extends('layouts.app')

@section('content')
<!-- Image Showcases -->
<section class="showcase">
    <div class="container p-0">
        <div class="row no-gutters">
            <div class="col-lg-6 mt-3">
                <h2 id="dj-room-name">{{ $room->name }}</h2>
                <p class="lead mb-0">When you use a theme created by Start Bootstrap, you know that the theme will look great on any device, whether it's a phone, tablet, or desktop the page will behave responsively!</p>
            </div>
            <div class="col-lg-6">
                <div class="embed-responsive embed-responsive-16by9 mt-5">
                    <!-- <iframe id="ytplayer" type="text/html" width="720" height="405"
                        src="https://www.youtube.com/embed/M7lc1UVf-VE"
                        frameborder="0" allowfullscreen></iframe> -->
                    <!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
                    <div id="yt_player"></div>
                </div>
                <form action="{{ route('song_request.store') }}" method="post" id="songRequestForm">
                    @csrf
                    <div class="input-group mb-3 mt-2">
                        <input type="hidden" id="room_id" name="room_id" value="{{ $room->id }}">
                        <input type="text" class="form-control" type='text' id="video_id" name='video_id' placeholder="Youtube URL, ex. https://www.youtube.com/watch?v=IcrbM1l_BoI"  aria-label="Youtuber URL" aria-describedby='basic-addon2'>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type='submit'>Add!</button>
                        </div>
                    </div>
                </form>
                <div id="song-request-list">
                    @forelse ($songRequests as $songRequest)
                        <div class="card col mb-2 card-{{ $songRequest->id }}">
                            <div class="card-body px-0">
                                <div class="row">
                                    <div class="col-4">
                                        <img src="{{ $songRequest->thumbnail_df }}" width="150" alt="{{ $songRequest->title }} Thumbnail">
                                    </div>
                                    <div class="col-8">
                                        @if(Auth::check())
                                            @if($room->id == Auth::user()->room()->first()->id && Auth::check())
                                                <button type="submit" class="close remove-song-button" aria-label="Close" data-id="{{ $songRequest->id }}">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            @endif
                                        @endif
                                        <h5 class="card-title">{{ $songRequest->title }}</h5>
                                        @if($songRequest->status == 'queue')
                                            <span class="badge badge-success">In Queue</span>
                                        @elseif($songRequest->status == 'played')
                                            <span class="badge badge-warning">Played</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card col mb-2 empty-request-card">
                            <div class="card-body">
                                <p class="card-text">Empty Request</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Delete Song -->
    <div id="delete-song-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Are You Sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to remove this song from list. Are you sure?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger delete-song-button-modal" data-id="">Yes, I'm sure</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
<script src="https://www.youtube.com/iframe_api"></script>

@if(Auth::check())
    @if($room->id == Auth::user()->room()->first()->id && Auth::check())
        <script>setRoomMasterTrue()</script>
    @endif
@endif

<script>
    var pusherInit = {
        "key": "{{ env('PUSHER_APP_KEY') }}",
        "cluster": "{{ env('PUSHER_APP_CLUSTER') }}",
    };
    pusherListenToSongTransaction(pusherInit);
    // checkNewestSongRequest("{{ env('APP_URL') }}:8000/api/song_request/{{ $room->id }}/status/queue?last=true");
    // 3. This function creates an <iframe> (and YouTube player)
    //    after the API code downloads.
    var player;
    function onYouTubeIframeAPIReady() {
        console.log('hai');
        player = new YT.Player('yt_player', {
            height: '390',
            width: '640',
            // videoId: '6cmMYouVsXg',
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    // 4. The API will call this function when the video player is ready.
    function onPlayerReady(event) {
        event.target.playVideo();
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.
    var done = false;
    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
            setTimeout(stopVideo, 6000);
            done = true;
        }
    }
    function stopVideo() {
        player.stopVideo();
    }
</script>
@endsection

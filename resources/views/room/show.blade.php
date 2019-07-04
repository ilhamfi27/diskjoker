@extends('layouts.app')

@section('content')
<!-- Image Showcases -->
<section class="showcase">
    <div class="container p-0">
        <div class="row no-gutters">
            <div class="col-lg-6 mt-3">
                <h2>{{ $room->name }}</h2>
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
                        <div class="card col mb-2">
                            <div class="card-body px-0">
                                <div class="row">
                                    <div class="col-4">
                                        <img src="{{ $songRequest->thumbnail_df }}" width="150" alt="{{ $songRequest->title }} Thumbnail">
                                    </div>
                                    <div class="col-8">
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
                        <div class="card col mb-2">
                            <div class="card-body">
                                <p class="card-text">Empty Request</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<script src="https://www.youtube.com/iframe_api"></script>

<script>
    // 3. This function creates an <iframe> (and YouTube player)
    //    after the API code downloads.
    var player;
    function onYouTubeIframeAPIReady() {
        console.log('hai');
        player = new YT.Player('yt_player', {
            height: '390',
            width: '640',
            videoId: '',
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

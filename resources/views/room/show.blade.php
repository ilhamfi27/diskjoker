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
                </div>
                <form action="{{ route('song_request.store') }}" method="post" id="songRequestForm">
                    @csrf
                    <div class="input-group mb-3 mt-2">
                        <input type="hidden" id="room_id" name="room_id" value="{{ $room->id }}">
                        <input type="text" class="form-control" type='text' id="url" name='url' placeholder="Youtuber URL" aria-label="Youtuber URL" aria-describedby='basic-addon2'>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type='submit'>Add!</button>
                        </div>
                    </div>
                </form>
                <div id="song-request-list">
                    @forelse ($songRequests as $songRequest)
                        <div class="card col mb-2">
                            <div class="card-body">
                                <h5 class="card-title">{{ $songRequest->url }}</h5>
                                <p class="card-text">Song List Description If Necessary.</p>
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

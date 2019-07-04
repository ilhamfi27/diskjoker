@extends('layouts.app')

@section('content')
<!-- Image Showcases -->
<section class="showcase">
    <div class="container p-0">
        <div class="row no-gutters">
            <div class="col-lg my-auto">
                <h2>{{ $room->name }} Setting</h2>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('room.update', $room->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{ $room->name }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="url">URL</label>
                            <input type="text" class="form-control" name="url" id="url" value="{{ $room->url }}">
                        </div>
                        <div class="w-100"></div>
                        <div class="form-group col-6">
                            <label for="max_song_per_user">Max Member Request</label>
                            <input type="text" class="form-control" name="max_song_per_user" id="max_song_per_user" value="{{ $room->max_song_per_user }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="user_max_song_limit_time">Max Member Request Delay Time (seconds)</label>
                            <input type="text" class="form-control" name="user_max_song_limit_time" id="user_max_song_limit_time" value="{{ $room->user_max_song_limit_time }}">
                        </div>
                        <div class="w-100"></div>
                        <div class="form-group col-6">
                            <label for="max_song_per_guest">Max Guest Request</label>
                            <input type="text" class="form-control" name="max_song_per_guest" id="max_song_per_guest" value="{{ $room->max_song_per_guest }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="guest_max_song_limit_time">Max Guest Request Delay Time (seconds)</label>
                            <input type="text" class="form-control" name="guest_max_song_limit_time" id="guest_max_song_limit_time" value="{{ $room->guest_max_song_limit_time }}">
                        </div>
                        <div class="w-100"></div>
                        <div class="form-group col-6 offset-md-6">
                            <label for="max_song_duration">Max Song Duration</label>
                            <input type="text" class="form-control" name="max_song_duration" id="max_song_duration" value="{{ $room->max_song_duration }}">
                        </div>
                        <div class="w-100"></div>
                        <div class="form-group col-6 offset-md-6">
                            <button type="submit" class="btn btn-primary float-right">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

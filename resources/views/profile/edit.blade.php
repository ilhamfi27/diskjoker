@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update', $user->id) }}" enctype='multipart/form-data'>
                        @csrf
                        @method('PATCH')
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="name">{{ __('Full Name') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-6">
                                <label for="nickname">{{ __('Nickname') }}</label>
                                <input id="nickname" type="text" class="form-control @error('nickname') is-invalid @enderror" name="nickname" value="{{ $biodata->nickname }}" autocomplete="nickname" autofocus>

                                @error('nickname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="w100"></div>

                            <div class="form-group col-6">
                                <label for="avatar">{{ __('Avatar') }}</label>
                                <input id="avatar" type="file" class="form-control @error('avatar') is-invalid @enderror" name="avatar" value="{{ $biodata->avatar }}" autocomplete="avatar" accept="image/*" autofocus>

                                @error('avatar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-6">
                                <label for="email">{{ __('E-Mail Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- div -->
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <img src="{{ $biodata->avatar }}" alt="{{ $user->name }}\'s Avatar" width="300">
                                    </div>
                                    <div class="col-6 p-0">
                                        <div class="form-group col-12 pl-1">
                                            <label for="password">{{ __('Password') }}</label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-12 pl-1">
                                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                        </div>

                                        <div class="form-group col-12 pl-1">
                                            <label for="old-password-confirm">{{ __('Confirm Old Password') }}</label>
                                            <input id="old-password-confirm" type="password" class="form-control @if ($message = Session::get('old_password_confirmation_error')) is-invalid @endif" name="old_password_confirmation">
                                            
                                            @if ($message = Session::get('old_password_confirmation_error'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-6 offset-md-6">
                                <button type="submit" class="btn btn-primary float-right">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

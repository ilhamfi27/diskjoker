<?php

namespace App\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Event;
use App\SongRequest;
use App\Events\SongRequestAdded;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        SongRequest::created(function ($songRequest){
            event(new SongRequestAdded($songRequest));
        });
    }
}

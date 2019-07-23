<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/pusher', function() {
    event(new App\Events\HelloPusherEvent('Hi there Pusher!'));
    return "Event has been sent!";
});
Route::get('/try_pusher', function(){
    return view('try_pusher.pusher');
});
Route::resource('room', 'RoomController')->except([
    'index'
]);
Route::resource('song_request', 'SongRequestController')->only([
    'store', 'destroy'
]);
// Route::post('song_request/{song_request}', 'SongRequestController@destroy')
    //  ->name('song_request.destroy');
Route::get('/', 'LandingController@index');
Route::resource('/profile', 'UserProfileController')->only([
    'edit', 'update'
])->middleware('auth');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

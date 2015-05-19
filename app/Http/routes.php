<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
    Route::post('/music/do_upload', 'MusicController@do_upload');

Route::group(['middleware' => 'auth'], function()
{
    Route::get('/', 'MusicController@index');
    Route::get('home', 'MusicController@index');

    Route::get('/music/upload', 'MusicController@upload');
    Route::resource('music', 'MusicController');
    Route::get('/artists/songs/{id}', 'ArtistsController@songs');
    Route::resource('artists', 'ArtistsController');
    Route::get('/albums/artist/{id}', 'AlbumsController@artist');
    Route::resource('albums', 'AlbumsController');
    Route::resource('songs', 'SongsController');
    Route::get('/playlists/get_list', 'PlaylistsController@get_list');
    Route::post('/playlists/add_song/{playlist}/{song}', 'PlaylistsController@add_song');
    Route::resource('playlists', 'PlaylistsController');
});

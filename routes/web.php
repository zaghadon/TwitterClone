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

Auth::routes();

Route::group(['middleware' => ['guest']], function () {
    Route::get('/', function () {
        return view('welcome');
    });
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('notifications', 'NotificationController@index');

    // Post Routes
    Route::post('p/{post}/like', 'Post\LikeController@like');
    Route::post('p/{post}/unlike', 'Post\LikeController@unlike');

    Route::post('p/{post}/report', 'Post\ReportController@store');

    Route::post('p/{post}/share', 'Post\ShareController@share');
    Route::post('p/{post}/unshare', 'Post\ShareController@unshare');

    // User Routes
    Route::post('u/{user}/block', 'User\BlockController@block');
    Route::post('u/{user}/unblock', 'User\BlockController@unblock');

    Route::post('u/{user}/follow', 'User\FollowController@follow');
    Route::post('u/{user}/unfollow', 'User\FollowController@unfollow');
});

Route::resource('p', 'PostController');

Route::get('{username}', 'UserController@show');
Route::get('{username}/followers', 'UserController@followers');
Route::get('{username}/following', 'UserController@following');

Route::resource('api/{user_id}/timeline', 'Api\TimelineController');
Route::get('api/{user_id}/p/liked', 'Api\Post\LikeController@index');

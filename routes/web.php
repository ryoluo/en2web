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
Route::get('/redirect/slack', 'OAuthController@redirect');
Route::get('/login/slack', 'OAuthController@login');
Route::get('/redirect/discord', 'OAuthController@redirectDiscord');
Route::get('/login/discord', 'OAuthController@loginDiscord');

Route::group(['prefix' => 'api'], function () {
    Route::post('/login', 'Api\Auth\LoginController@login');
    Route::get('/check', 'Api\Auth\LoginController@check');
    Route::post('/logout', 'Api\Auth\LoginController@logout');
    Route::post('/refresh', 'Api\Auth\LoginController@refresh');
    Route::post('/reset', 'Api\Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/reset/password', 'Api\Auth\ResetPasswordController@reset');
    Route::get('/me', 'Api\Auth\LoginController@me');

    Route::post('/register', 'Api\Auth\RegisterController@register');
    Route::post('/register/verify', 'Api\Auth\RegisterController@verify');

    Route::get('/notes', 'Api\NotesController@index');
    Route::get('/notes/{note}', 'Api\NotesController@get')->where('note', '[0-9]+');
    Route::post('/notes', 'Api\NotesController@store');
    Route::put('/notes/{note}', 'Api\NotesController@update')->where('note', '[0-9]+');
    Route::delete('/notes/{note}', 'Api\NotesController@destroy')->where('note', '[0-9]+');
    Route::put('/notes/{note}/fav', 'Api\NotesController@fav')->where('note', '[0-9]+');

    Route::get('/notes/categories', 'Api\NotesController@categories');
    Route::get('/notes/tags', 'Api\NotesController@tags');

    Route::get('/users', 'Api\UsersController@index');
    Route::get('/users/{user}', 'Api\UsersController@get');
    Route::patch('/users/update', 'Api\UsersController@update');
    Route::post('/users/upload', 'Api\UsersController@upload');
    Route::post('/users/icon', 'Api\UsersController@saveIcon');
    Route::post('/users/cover', 'Api\UsersController@saveCover');

    Route::get('/countries', 'Api\CountriesController@index');
});

Route::group(['prefix' => 'open'], function () {
    Route::get('/twitter/{id}', 'UrlSchemeController@twitter');
    Route::get('/instagram/{id}', 'UrlSchemeController@instagram');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

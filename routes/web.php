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
Route::group(['prefix' => 'open'], function () {
    Route::get('/twitter/{id}', 'UrlSchemeController@twitter');
    Route::get('/instagram/{id}', 'UrlSchemeController@instagram');
});

Route::get('/{any}', function() {
    return view('app');
})->where('any', '.*');

// Route::get('/how-to-register', function(){
//     return view('web.how_to_register');
// });
// // 会員のみ
// Route::middleware(['auth'])->group(function () {
//     Route::get('/', 'UserController@showHome');

//     /**
//      * Mypage
//      */
//     Route::get('/mypage', 'UserController@showMyPage');
//     Route::get('/mypage/edit', 'UserController@editMyPage');
//     Route::patch('/mypage/update', 'UserController@updateMyPage');
//     Route::get('/mypage/upload_avater', 'UserController@uploadAvater');
//     Route::post('/mypage/upload_avater', 'UserController@uploadAvater_confirm');
//     Route::patch('/mypage/upload_avater', 'UserController@uploadAvater_save');
    
//     Route::get('/mypage/upload_coverimg', 'UserController@uploadCoverimg');
//     Route::post('/mypage/upload_coverimg', 'UserController@uploadCoverimg_confirm');
//     Route::patch('/mypage/upload_coverimg', 'UserController@uploadCoverimg_save');

//     /**
//      * Users
//      */
//     Route::resource('users', 'UsersController', ['only' => ['index','show']]);
//     /**
//      * Countries
//      */
//     Route::resource('countries', 'CountriesController', ['only' => ['index','show']]);

//     /**
//      * Notes
//      */
//     //resource
//     Route::resource('notes', 'NotesController');
//     Route::get('/notes/{note}', 'NotesController@show')->where('note', '[0-9]+'); //override
//     //create
//     Route::post('/notes/create', 'NotesController@confirm');
//     //show
//     Route::get('/notes/all', 'NotesController@showAll');
//     Route::get('/notes/best', 'NotesController@showBest');
//     Route::get('/notes/like', 'NotesController@showLike');
//     Route::get('/categories/{category}/notes', 'NotesController@showByCategory');
//     Route::get('/tags/{tag}/notes', 'NotesController@showByTag');
//     Route::get('/users/{user}/notes', 'NotesController@showByAuthor');
//     Route::get('/countries/{country}/notes', 'NotesController@showByCountry');
//     //edit
//     Route::post('/notes/{note}/edit', 'NotesController@editConfirm');
//     //delete
//     Route::get('/notes/{note}/delete', 'NotesController@deleteConfirm');
//     //search
//     Route::get('/notes/search', 'NotesController@showSearchForm');
//     Route::get('/notes/search/result', 'NotesController@search');

//     /**
//      * Calendar
//      */
//     Route::get('/calendar', 'CalendarController@index');
//     Route::get('/calendar/create', 'CalendarController@create');
//     Route::post('/calendar', 'CalendarController@store');
//     Route::get('/calendar/{eventId}', 'CalendarController@edit');
//     Route::post('/calendar/{eventId}', 'CalendarController@update');
//     Route::delete('/calendar/{eventId}', 'CalendarController@destroy');

//     /**
//      * Support
//      */
//     Route::get('/support', 'UserController@support');
//     Route::post('/support', 'UserController@sendMail');

//     /**
//      * 出席関連
//      */
//     Route::middleware(['admin'])->group(function () {
//         Route::get('/attendance/manager', 'AttendanceController@index');
//         Route::post('/meeting/add', 'AttendanceController@addMeeting');
//         Route::post('/meeting/{meeting}/toggle', 'AttendanceController@toggleMeeting');
//         Route::post('/meeting/{meeting}/complete', 'AttendanceController@completeMeeting');
//         Route::post('/meeting/{meeting}/cancel', 'AttendanceController@cancelMeeting');
//     });
//     Route::post('/ajax/attend', 'AttendanceController@attend');
//     Route::get('/attendance', 'AttendanceController@showResults');

//     /**
//      * NoteのAjax
//      */
//     Route::group(['prefix' => 'ajax'], function () {
//         Route::post('/notes/fav', 'NotesController@fav');
//         //LINE share
//         Route::post('/notes/{note}/share', 'NotesController@share');
//     });

//     /**
//      * 他アプリのURL Scheme
//      */
//     Route::group(['prefix' => 'open'], function () {
//         Route::get('/twitter/{id}', 'UrlSchemeController@twitter');
//         Route::get('/instagram/{id}', 'UrlSchemeController@instagram');
//     });
// });

// /**
//  * Auth and Register
//  */
// Auth::routes();
// Route::post('register/confirm', 'Auth\RegisterController@confirm')->name('register.confirm');
// Route::post('register/confirm/existinguser', 'Auth\RegisterController@registerExistingUser')->name('register.existing.user');
// Route::get('register/verify/{token}', 'Auth\RegisterController@showForm')->name('show.form');
// Route::post('register/main_confirm', 'Auth\RegisterController@mainCheck')->name('register.main.confirm');
// Route::post('register/main_register', 'Auth\RegisterController@mainRegister')->name('register.main.registered');

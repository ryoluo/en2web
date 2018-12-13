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

Route::get('/', function () { return view('auth.login'); });//return view('hp.index'); });
// Route::get('/about_us', function () { return view('hp.about_us'); });
// Route::get('/study_abroad', function () { return view('hp.study_abroad'); });
// Route::get('/activities', function () { return view('hp.activities'); });
// Route::get('/achievements', function () { return view('hp.achievements'); });
// Route::get('/join_contact', function () { return view('hp.join_contact'); });



Route::get('/home', 'UserController@showHome')->middleware('auth');
Route::get('/mypage', 'UserController@showMyPage')->middleware('auth');
Route::get('/mypage/edit', 'UserController@editMyPage')->middleware('auth');
Route::patch('/mypage/update', 'UserController@updateMyPage')->middleware('auth');

Route::resource('users', 'UsersController')->middleware('auth');

Route::resource('countries', 'CountriesController')->middleware('auth');

Route::resource('notes', 'NotesController')->middleware('auth');
Route::post('/notes/create', 'NotesController@Confirm')->middleware('auth');
Route::post('/notes/{note}/edit', 'NotesController@editConfirm')->middleware('auth');
Route::get('/notes/{note}/delete', 'NotesController@deleteConfirm')->middleware('auth');

Route::get('/categories/{category}/notes', 'NotesController@showByCategory')->middleware('auth');
Route::get('/tags/{tag}/notes', 'NotesController@showByTag')->middleware('auth');
Route::get('/users/{user}/notes', 'NotesController@showByAuthor')->middleware('auth');
Route::get('/countries/{country}/notes', 'NotesController@showByCountry')->middleware('auth');
// Route::get('/users', 'UsersController@index');
// Route::get('/users/create', 'UsersController@create');
// Route::get('/users/{user}', 'UsersController@show');
// Route::post('/users', 'UsersController@store');
// Route::get('/users/{user}/edit', 'UsersController@edit');
// Route::patch('/users/{user}', 'UsersController@update');
// Route::delete('/users/{user}', 'UsersController@destroy');



/*Register*/
Auth::routes();
Route::post('register/confirm', 'Auth\RegisterController@confirm')->name('register.confirm');
Route::post('register/confirm/existinguser', 'Auth\RegisterController@registerExistingUser')->name('register.existing.user');
Route::get('register/verify/{token}', 'Auth\RegisterController@showForm');
Route::post('register/main_confirm', 'Auth\RegisterController@mainCheck')->name('register.main.confirm');
Route::post('register/main_register', 'Auth\RegisterController@mainRegister')->name('register.main.registered');

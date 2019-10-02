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

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/dashboard', 'DashboardController@index');

Route::get('/dashboard/getPermitDetails', 'DashboardController@getPermitDetails');

Route::get('/dashboard/getNotes', 'DashboardController@getNotes');

Route::put('/dashboard/updateNotes', 'DashboardController@updateNotes');

Route::get('/FAQ', 'FAQController@index');

Route::get('/about-us', 'AboutUsController@index');

Route::get('/new-permits', 'NewPermitsController@index');

Route::get('/new-permits/getNotes', 'NewPermitsController@getNotes');

Route::put('/new-permits/updateNotes', 'NewPermitsController@updateNotes');

Route::get('/new-permits/getPermitDetails', 'NewPermitsController@getPermitDetails');

Route::get('/welbore', 'WelboreController@index');


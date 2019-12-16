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

Route::get('/dashboard/getLeaseDetails', 'DashboardController@getLeaseDetails');

Route::get('/dashboard/getNotes', 'DashboardController@getNotes');

Route::put('/dashboard/updateNotes', 'DashboardController@updateNotes');

Route::put('/dashboard/updateAssignee', 'DashboardController@updateAssignee');

Route::get('/FAQ', 'FAQController@index');

Route::get('/about-us', 'AboutUsController@index');

Route::get('/new-permits', 'NewPermitsController@index');

Route::get('/new-permits/getNotes', 'NewPermitsController@getNotes');

Route::put('/new-permits/updateNotes', 'NewPermitsController@updateNotes');

Route::put('/new-permits/updateAssignee', 'NewPermitsController@updateAssignee');

Route::get('/new-permits/getPermitDetails', 'NewPermitsController@getPermitDetails');

Route::get('/admin', 'AdminController@index');

Route::get('/welbore', 'WelboreController@index');


Route::get('/mineral-owner/{operator?}', 'MineralOwnersController@index');


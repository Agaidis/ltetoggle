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

Route::get('/mm-platform', 'DashboardController@index');

Route::get('/mm-platform/getLeaseDetails', 'DashboardController@getLeaseDetails');

Route::get('/mm-platform/getNotes', 'DashboardController@getNotes');

Route::put('/mm-platform/updateNotes', 'DashboardController@updateNotes');

Route::put('/mm-platform/updateAssignee', 'DashboardController@updateAssignee');

Route::get('/FAQ', 'FAQController@index');

Route::get('/about-us', 'AboutUsController@index');

Route::get('/new-permits', 'NewPermitsController@index');

Route::get('/new-permits/getNotes', 'NewPermitsController@getNotes');

Route::put('/new-permits/updateNotes', 'NewPermitsController@updateNotes');

Route::put('/new-permits/updateAssignee', 'NewPermitsController@updateAssignee');

Route::get('/new-permits/getPermitDetails', 'NewPermitsController@getPermitDetails');

Route::get('/admin', 'AdminController@index');

Route::get('/welbore', 'WelboreController@index');


Route::get('/mineral-owner/{operator?}/{reporter?}', 'MineralOwnersController@index');


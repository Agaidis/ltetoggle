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

Route::get('/admin', 'AdminController@index');

Route::get('/welbore', 'WelboreController@index');

Route::get('/FAQ', 'FAQController@index');

Route::get('/about-us', 'AboutUsController@index');


/*                              MM-PLATFORM                 */
Route::get('/mm-platform', 'DashboardController@index');

Route::get('/mm-platform/getLeaseDetails', 'DashboardController@getLeaseDetails');

Route::get('/mm-platform/getNotes', 'DashboardController@getNotes');

Route::put('/mm-platform/updateNotes', 'DashboardController@updateNotes');

Route::put('/mm-platform/updateAssignee', 'DashboardController@updateAssignee');


/*              PERMITS/DASHBOARD                   */
Route::get('/new-permits', 'NewPermitsController@index');

Route::get('/new-permits/getNotes', 'NewPermitsController@getNotes');

Route::put('/new-permits/updateNotes', 'NewPermitsController@updateNotes');

Route::put('/new-permits/updateAssignee', 'NewPermitsController@updateAssignee');

Route::get('/new-permits/getPermitDetails', 'NewPermitsController@getPermitDetails');


/*                      MINERAL OWNER/LEASE PAGE                        */
Route::get('/mineral-owner/{operator?}/{reporter?}/{id?}', 'MineralOwnersController@index');

Route::get('/mineral-owners/getNotes', 'MineralOwnersController@getNotes');

Route::put('/mineral-owner/updateNotes', 'MineralOwnersController@updateNotes');

Route::put('/mineral-owner/updateAssignee', 'MineralOwnersController@updateAssignee');

Route::put('/mineral-owner/updatePhoneNumbers', 'MineralOwnersController@updatePhoneNumbers');

Route::put('/mineral-owner/updateWellType', 'MineralOwnersController@updateWellType');

Route::get('/mineral-owner', 'MineralOwnersController@getOwnerInfo');

Route::post('/mineral-owner/addPhone', 'MineralOwnersController@addPhone');

Route::post('/mineral-owner/softDeletePhone', 'MineralOwnersController@softDeletePhone');


/*                      Owner Page                          */
Route::get('/owner/{ownerName?}', 'OwnersController@index');
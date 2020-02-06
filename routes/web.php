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

Route::get('/welbore', 'WelboreController@index')->middleware('auth');

Route::get('/user-mmp', 'UserMMPController@index')->middleware('auth');

Route::get('/FAQ', 'FAQController@index');

Route::get('/about-us', 'AboutUsController@index');


/*                              ADMIN AREA                  */
Route::get('/admin', 'AdminController@index')->middleware('auth');

Route::get('/admin/updatePermits', 'AdminController@updatePermits')->middleware('auth');


/*                              MM-PLATFORM                 */
Route::get('/mm-platform', 'DashboardController@index')->middleware('auth');

Route::get('/mm-platform/getLeaseDetails', 'DashboardController@getLeaseDetails')->middleware('auth');

Route::get('/mm-platform/getNotes', 'DashboardController@getNotes')->middleware('auth');

Route::put('/mm-platform/updateNotes', 'DashboardController@updateNotes')->middleware('auth');

Route::put('/mm-platform/updateAssignee', 'DashboardController@updateAssignee')->middleware('auth');


/*              PERMITS/DASHBOARD                   */
Route::get('/new-permits', 'NewPermitsController@index')->middleware('auth');

Route::get('/new-permits/getNotes', 'NewPermitsController@getNotes')->middleware('auth');

Route::put('/new-permits/updateNotes', 'NewPermitsController@updateNotes')->middleware('auth');

Route::put('/new-permits/updateAssignee', 'NewPermitsController@updateAssignee')->middleware('auth');

Route::get('/new-permits/getPermitDetails', 'NewPermitsController@getPermitDetails')->middleware('auth');

Route::post('/new-permits/delete/delete-note', 'NewPermitsController@deleteNote')->middleware('auth');


/*                      MINERAL OWNER/LEASE PAGE                        */
Route::get('/mineral-owner/{operator?}/{reporter?}/{id?}', 'MineralOwnersController@index')->middleware('auth');

Route::get('/mineral-owners/getNotes', 'MineralOwnersController@getNotes')->middleware('auth');

Route::put('/mineral-owner/updateNotes', 'MineralOwnersController@updateNotes')->middleware('auth');

Route::put('/mineral-owner/updateAssignee', 'MineralOwnersController@updateAssignee')->middleware('auth');

Route::put('/mineral-owner/updatePhoneNumbers', 'MineralOwnersController@updatePhoneNumbers')->middleware('auth');

Route::put('/mineral-owner/updateWellType', 'MineralOwnersController@updateWellType')->middleware('auth');

Route::put('/mineral-owner/updateFollowUp', 'MineralOwnersController@updateFollowUp')->middleware('auth');

Route::get('/mineral-owners', 'MineralOwnersController@getOwnerInfo')->middleware('auth');

Route::post('/mineral-owner/addPhone', 'MineralOwnersController@addPhone')->middleware('auth');

Route::post('/mineral-owner/softDeletePhone', 'MineralOwnersController@softDeletePhone')->middleware('auth');

Route::get('/mineral-owners/getOwnerNumbers', 'MineralOwnersController@getOwnerNumbers')->middleware('auth');

Route::post('/mineral-owners/updateAcreage', 'MineralOwnersController@updateAcreage')->middleware('auth');

Route::post('mineral-owners/delete/delete-note', 'MineralOwnersController@deleteNote')->middleware('auth');



/*                      Owner Page                          */
Route::get('/owner/{ownerName?}', 'OwnersController@index')->middleware('auth');
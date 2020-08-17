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

Route::get('/justus-mmp', 'UserMMPController@justus')->middleware('auth');


/*                              ADMIN AREA                  */
Route::get('/admin', 'AdminController@index')->middleware('auth');

Route::get('/admin/updatePermits', 'AdminController@updatePermits')->middleware('auth');

/*                  PERMIT STORAGE              */
Route::get('/permit-storage', 'PermitStorageController@index')->middleware('auth');
Route::get('/permit-storage/sendBack', 'PermitStorageController@sendBack')->middleware('auth');


/*              PERMITS/MM-PLATFORM                     */
Route::get('/mm-platform', 'MMPController@index')->middleware('auth');

Route::get('/new-permits/getNotes', 'MMPController@getNotes')->middleware('auth');

Route::put('/new-permits/updateNotes', 'MMPController@updateNotes')->middleware('auth');

Route::put('/new-permits/updateAssignee', 'MMPController@updateAssignee')->middleware('auth');

Route::post('/new-permits/updateStatus', 'MMPController@updateStatus')->middleware('auth');

Route::put('/new-permits/stitchLeaseToPermit', 'MMPController@stitchLeaseToPermit')->middleware('auth');


Route::get('/new-permits/getPermitDetails', 'MMPController@getPermitDetails')->middleware('auth');

Route::post('/new-permits/delete/delete-note', 'MMPController@deleteNote')->middleware('auth');

Route::get('/new-permits/storePermit', 'MMPController@storePermit')->middleware('auth');

Route::put('/update-prices', 'MMPController@updatePrices');



/*                      NON PRODUCING LEASE PAGE                        */

Route::put('/non-producing-lease/updateAssignee', 'nonProducingOwnersController@updateAssignee')->middleware('auth');

Route::put('/non-producing-lease/updateFollowUp', 'nonProducingOwnersController@updateFollowUp')->middleware('auth');

Route::get('/non-producing-lease-update/update/update-well-type-update', 'nonProducingOwnersController@updateWellType')->middleware('auth');

Route::post('/non-producing-lease/update/OwnerPrice', 'nonProducingOwnersController@updateOwnerPrice')->middleware('auth');

Route::get('/non-producing-lease/getNotes', 'nonProducingOwnersController@getNotes')->middleware('auth');

Route::put('/non-producing-lease/updateNotes', 'nonProducingOwnersController@updateNotes')->middleware('auth');

Route::post('non-producing-lease/delete/delete-note', 'nonProducingOwnersController@deleteNote')->middleware('auth');

Route::get('/non-producing-lease/getOwnerNumbers', 'nonProducingOwnersController@getOwnerNumbers')->middleware('auth');

Route::post('/non-producing-lease/addPhone', 'nonProducingOwnersController@addPhone')->middleware('auth');

Route::put('/non-producing-lease/pushPhoneNumber', 'nonProducingOwnersController@pushPhoneNumber')->middleware('auth');

Route::post('/non-producing-lease/softDeletePhone', 'nonProducingOwnersController@softDeletePhone')->middleware('auth');

Route::get('/non-producing-mineral-owners', 'nonProducingOwnersController@getOwnerInfo')->middleware('auth');





/*                                  LEASE PAGE                        */
//GET OWNERS INFO
Route::get('/lease-page/{interestArea?}/{isProducing?}/{id?}', 'LeasePageController@index')->middleware('auth');

Route::get('/mineral-owners', 'MineralOwnersController@getOwnerInfo')->middleware('auth');

Route::post('/mineral-owners/updateLeaseNames', 'MineralOwnersController@updateLeaseName')->middleware('auth');

Route::post('/mineral-owners/updateWellNames', 'MineralOwnersController@updateWellName')->middleware('auth');




//Well Details
Route::post('/mineral-owners/get/getWellDetails', 'MineralOwnersController@getWellInfo')->middleware('auth');

//NOTES
Route::get('/mineral-owners/getNotes', 'MineralOwnersController@getNotes')->middleware('auth');

Route::put('/mineral-owner/updateNotes', 'MineralOwnersController@updateNotes')->middleware('auth');

Route::post('mineral-owners/delete/delete-note', 'MineralOwnersController@deleteNote')->middleware('auth');

//ASSIGNEE WELLTYPE AND FOLLOWUP
Route::put('/mineral-owner/updateAssignee', 'MineralOwnersController@updateAssignee')->middleware('auth');

Route::put('/mineral-owner/updateWellType', 'MineralOwnersController@updateWellType')->middleware('auth');

Route::put('/mineral-owner/updateFollowUp', 'MineralOwnersController@updateFollowUp')->middleware('auth');


//PHONE NUMBERS
Route::get('/mineral-owners/getOwnerNumbers', 'MineralOwnersController@getOwnerNumbers')->middleware('auth');

Route::post('/mineral-owner/addPhone', 'MineralOwnersController@addPhone')->middleware('auth');


Route::put('/mineral-owner/pushPhoneNumber', 'MineralOwnersController@pushPhoneNumber')->middleware('auth');

Route::post('/mineral-owner/softDeletePhone', 'MineralOwnersController@softDeletePhone')->middleware('auth');

Route::get('/pushed-phone-numbers', 'PushedPhoneNumbersController@index')->middleware('auth');

Route::put('/pushed-phone-numbers/updatePhoneNumber', 'PushedPhoneNumbersController@updatePhoneNumber')->middleware('auth');

Route::post('/pushed-phone-numbers/insertPhoneNumber', 'PushedPhoneNumbersController@insertPhoneNumber')->middleware('auth');

// ACREAGE
Route::post('/mineral-owners/updateAcreage', 'MineralOwnersController@updateAcreage')->middleware('auth');

//PRICE
Route::post('/mineral-owners/update/OwnerPrice', 'MineralOwnersController@updateOwnerPrice')->middleware('auth');

/*                      OWNER PAGE                     */
Route::get('/owner/{ownerName?}', 'OwnersController@index')->middleware('auth');
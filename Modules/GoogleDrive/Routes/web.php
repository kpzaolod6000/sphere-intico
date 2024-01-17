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

Route::group(['middleware' => 'PlanModuleCheck:GoogleDrive'], function ()
{

    Route::get('/auth/google', 'GoogleDriveController@redirectToGoogle')->name('auth.google');
    Route::get('/auth/google/callback', 'GoogleDriveController@handleGoogleCallback')->name('auth.google.callback');


    Route::any('googledrive/folder/assigned/{module?}', 'GoogleDriveController@assign_folder_store')->name('assigned.folder')->middleware(['auth']);
    Route::any('googledrive/folder/assign/{module?}', 'GoogleDriveController@assign_folder')->name('assign.folder')->middleware(['auth']);


    Route::any('googledrive/folder/create/{module}/{parentid?}', 'GoogleDriveController@create_folder')->name('create.new.folder')->middleware(['auth']);
    Route::any('googledrive/folder/store/{module}/{parentid?}', 'GoogleDriveController@store_folder')->name('store.new.folder')->middleware(['auth']);


    Route::any('googledrive/uploadfiles/{modulename}', 'GoogleDriveController@uploadfiles_store')->name('upload.file.store')->middleware(['auth']);
    Route::get('googledrive/{module}', 'GoogleDriveController@uploadfiles_create')->name('upload.file.create')->middleware(['auth']);
    Route::get('googledrive/delete/{folderid}', 'GoogleDriveController@delete_file')->name('file.delete')->middleware(['auth']);

    Route::get('googledrives/{module}/{folderid?}/{view?}', 'GoogleDriveController@index')->name('googledrive.index')->middleware(['auth']);
    Route::get('googledrive/{module}/{folderid?}/{view?}', 'GoogleDriveController@getmodulefiles')->name('googledrive.module.index')->middleware(['auth']);
    Route::post('google-drive-settings-save', 'GoogleDriveController@GoogleDriveSettingsStore')->name('google.drive.setting.store')->middleware(['auth']);

});

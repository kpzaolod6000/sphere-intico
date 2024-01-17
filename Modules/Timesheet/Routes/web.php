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
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'PlanModuleCheck:Timesheet'], function ()
{
    Route::resource('timesheet', 'TimesheetController')->middleware(['auth']);
});
Route::get('/totalhours', 'TimesheetController@totalhours')->name('totalhours');
Route::get('gethours/{id}', 'TimesheetController@gethours')->name('gethours');
Route::post('/gettask', 'TimesheetController@gettask')->name('gettask');


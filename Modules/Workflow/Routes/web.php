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

// Route::prefix('workflow')->group(function() {
//     Route::get('/', 'WorkflowController@index');
// });

use Illuminate\Support\Facades\Route;
 
Route::group(['middleware' => 'PlanModuleCheck:Workflow'], function () {
Route::resource('workflow', 'WorkflowController')->middleware(['auth']);

Route::POST('/workflow/getfielddata', 'WorkflowController@getfielddata')->name('workflow.getfielddata')->middleware(['auth']);
Route::POST('/workflow/getcondition', 'WorkflowController@getcondition')->name('workflow.getcondition')->middleware(['auth']);
Route::POST('/workflow/attribute', 'WorkflowController@attribute')->name('workflow.attribute')->middleware(['auth']);
Route::POST('/workflow/modules', 'WorkflowController@module')->name('workflow.modules')->middleware(['auth']);
Route::POST('/workflow/event', 'WorkflowController@event')->name('workflow.event')->middleware(['auth']);

});

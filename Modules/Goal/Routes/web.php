<?php

use Illuminate\Support\Facades\Route;
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

Route::group(['middleware' => 'PlanModuleCheck:Goal'], function () {
    Route::resource('goal', 'GoalController')->middleware(
        [
            'auth'
        ]
    );

    //Goal import
    Route::get('goal/import/export', 'GoalController@fileImportExport')->name('goal.file.import')->middleware(['auth']);
    Route::post('goal/import', 'GoalController@fileImport')->name('goal.import')->middleware(['auth']);
    Route::get('goal/import/modal', 'GoalController@fileImportModal')->name('goal.import.modal')->middleware(['auth']);
    Route::post('goal/data/import/', 'GoalController@goalImportdata')->name('goal.import.data')->middleware(['auth']);
});

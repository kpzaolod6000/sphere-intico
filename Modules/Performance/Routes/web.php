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


//Goal type
Route::group(['middleware' => 'PlanModuleCheck:Performance'], function ()
 {
    Route::resource('goaltype', 'GoalTypeController')->middleware(
        [
            'auth',

        ]
    );

    //GoalTracking
    Route::resource('goaltracking', 'GoalTrackingController')->middleware(
        [
            'auth',

        ]
    );
    Route::get('goaltracking-grid', 'GoalTrackingController@grid')->name('goaltracking.grid')->middleware(
        [
            'auth',

        ]
    );

    //performanceType
    Route::resource('performanceType', 'PerformanceTypeController')->middleware(
        [
            'auth',
        ]
    );

    //competencies
    Route::resource('competencies', 'CompetenciesController')->middleware(
        [
            'auth',
        ]
    );

    //indicator
    Route::resource('indicator', 'IndicatorController')->middleware(
        [
            'auth',
        ]
    );
    Route::post('employee/json', 'EmployeeController@json')->name('employee.json')->middleware(
        [
            'auth',
        ]
    );

    //appraisal
    Route::resource('appraisal', 'AppraisalController')->middleware(
        [
            'auth',
        ]
    );
    Route::post('/appraisals', 'AppraisalController@empByStar')->name('empByStar')->middleware(['auth']);
    Route::post('/appraisals1', 'AppraisalController@empByStar1')->name('empByStar1')->middleware(['auth']);
    Route::post('/getemployee', 'AppraisalController@getemployee')->name('getemployee');
});

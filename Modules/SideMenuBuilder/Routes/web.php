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


Route::group(['middleware' => 'PlanModuleCheck:SideMenuBuilder'], function () {

    Route::resource('sidemenubuilder', 'SideMenuBuilderController')->middleware(
        [
            'auth',
        ]
    );

    Route::get('iframe/{id}', 'SideMenuBuilderController@iFrameData')->name('sidemenubuilder.iframe')->middleware(
        [
            'auth',
        ]
    );

    
});

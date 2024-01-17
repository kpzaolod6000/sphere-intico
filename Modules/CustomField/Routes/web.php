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



Route::group(['middleware' => 'PlanModuleCheck:CustomField'], function () {
    Route::resource('custom-field', 'CustomFieldController')->middleware(['auth']);

    Route::get('custom-field/get_module/{module_name}', ['as' => 'custom_field.get.module', 'uses' => 'CustomFieldController@get_module_list'])->middleware(['auth']);
});

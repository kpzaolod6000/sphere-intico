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


Route::group(['middleware' => 'PlanModuleCheck:FormBuilder'], function ()
{
    Route::prefix('formbuilder')->group(function() {
        Route::get('/', 'FormBuilderController@index');
    });

    // Form Builder
    Route::resource('form_builder', 'FormBuilderController')->middleware(['auth']);

    // Form Response
    Route::get('/form_response/{id}',['as' => 'form.response','uses' =>'FormBuilderController@viewResponse'])->middleware(['auth']);
    Route::get('/response/{id}',['as' => 'response.detail','uses' =>'FormBuilderController@responseDetail'])->middleware(['auth']);

    // Form Field Bind
    Route::get('/form_field/{id}',['as' => 'form.field.bind','uses' =>'FormBuilderController@formFieldBind'])->middleware(['auth']);
    Route::post('/form_field_store/{id}',['as' => 'form.bind.store','uses' =>'FormBuilderController@bindStore'])->middleware(['auth']);

    // Form Field
    Route::get('/form_builder/{id}/field',['as' => 'form.field.create','uses' =>'FormBuilderController@fieldCreate'])->middleware(['auth']);
    Route::post('/form_builder/{id}/field',['as' => 'form.field.store','uses' =>'FormBuilderController@fieldStore'])->middleware(['auth']);
    Route::get('/form_builder/{id}/field/{fid}/show',['as' => 'form.field.show','uses' =>'FormBuilderController@fieldShow'])->middleware(['auth']);
    Route::get('/form_builder/{id}/field/{fid}/edit',['as' => 'form.field.edit','uses' =>'FormBuilderController@fieldEdit'])->middleware(['auth']);
    Route::put('/form_builder/{id}/field/{fid}',['as' => 'form.field.update','uses' =>'FormBuilderController@fieldUpdate'])->middleware(['auth']);
    Route::delete('/form_builder/{id}/field/{fid}',['as' => 'form.field.destroy','uses' =>'FormBuilderController@fieldDestroy'])->middleware(['auth']);
});
    // Form link base view
    Route::get('/form/{code}',['as' => 'form.view','uses' =>'FormBuilderController@formView']);
    Route::post('/form_view_store',['as' => 'form.view.store','uses' =>'FormBuilderController@formViewStore']);


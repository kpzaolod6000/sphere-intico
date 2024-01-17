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

// Route::prefix('contract')->group(function() {
//     Route::get('/', 'ContractController@index');
// });


Route::group(['middleware' => 'PlanModuleCheck:Contract'], function ()
{
    Route::resource('contract_type', 'ContractTypeController')->middleware(['auth']);
    Route::resource('contract', 'ContractController')->middleware(['auth']);
    Route::get('contract-grid', 'ContractController@grid')->name('contract.grid');
    Route::post('/contract_status_edit/{id}', 'ContractController@contract_status_edit')->name('contract.status')->middleware(['auth']);

    Route::get('/contract/copy/{id}',['as' => 'contracts.copy','uses' =>'ContractController@copycontract'])->middleware(['auth']);
    Route::post('/contract/copy/store/{id}',['as' => 'contracts.copy.store','uses' =>'ContractController@copycontractstore'])->middleware(['auth']);


    Route::post('contract/{id}/description', 'ContractController@descriptionStore')->name('contracts.description.store')->middleware(['auth']);
    Route::post('/contract/{id}/file', ['as' => 'contracts.file.upload','uses' => 'ContractController@fileUpload',])->middleware(['auth']);
    Route::get('/contract/{id}/file/{fid}', ['as' => 'contracts.file.download','uses' => 'ContractController@fileDownload',])->middleware(['auth']);
    Route::delete('/contract/{id}/file/delete/{fid}', ['as' => 'contracts.file.delete','uses' => 'ContractController@fileDelete',])->middleware(['auth']);
    Route::post('/contract/{id}/comment', ['as' => 'contract.comment.store', 'uses' => 'ContractController@commentStore',]);
    Route::get('/contract/{id}/comment', ['as' => 'contract.comment.destroy','uses' => 'ContractController@commentDestroy',]);
    Route::post('/contract/{id}/note', ['as' => 'contracts.note.store','uses' => 'ContractController@noteStore',])->middleware(['auth']);
    Route::get('/contract/{id}/note', ['as' => 'contracts.note.destroy','uses' => 'ContractController@noteDestroy',])->middleware(['auth']);


    Route::get('contract/{id}/get_contract', 'ContractController@printContract')->name('get.contract');
    Route::get('contract/pdf/{id}', 'ContractController@pdffromcontract')->name('contract.download.pdf');

    Route::get('/signature/{id}', 'ContractController@signature')->name('signature')->middleware(['auth']);
    Route::post('/signaturestore', 'ContractController@signatureStore')->name('signaturestore')->middleware(['auth']);

    Route::get('/contract/{id}/mail', ['as' => 'send.mail.contract','uses' => 'ContractController@sendmailContract',]);

    Route::post('contract/setting/store', 'ContractController@setting')->name('contract.setting.store')->middleware(['auth']);

    Route::post('/getproject', 'ContractController@getProject')->name('getproject');
});

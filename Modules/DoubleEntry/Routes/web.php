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
Route::group(['middleware' => 'PlanModuleCheck:DoubleEntry'], function ()
{

    Route::prefix('doubleentry')->group(function() {
        Route::get('/', 'DoubleEntryController@index');
    });



    Route::resource('journal-entry', 'JournalEntryController')->middleware(['auth']);
    Route::post('journal-entry/account/destroy', 'JournalEntryController@accountDestroy')->name('journal.account.destroy')->middleware(['auth']);
    Route::delete('journal-entry/journal/destroy/{item_id}', 'JournalEntryController@journalDestroy')->name('journal.destroy')->middleware(['auth']);


    Route::get('report/ledger/{account?}', 'ReportController@ledgerReport')->name('report.ledger');

//    Route::get('report/balance-sheet', 'ReportController@balanceSheetReport')->name('report.balance.sheet');



    Route::post('print/balance-sheet/{view?}', 'ReportController@balanceSheetPrint')->name('balance.sheet.print');
    Route::get('report/balance-sheet/{view?}', 'ReportController@balanceSheet')->name('report.balance.sheet');

    Route::get('report/profit-loss/{view?}', 'ReportController@profitLoss')->name('report.profit.loss');
    Route::post('print/profit-loss/{view?}', 'ReportController@profitLossPrint')->name('profit.loss.print');

    Route::get('report/trial-balance', 'ReportController@trialBalanceReport')->name('report.trial.balance');
    Route::post('print/trial-balance', 'ReportController@trialBalancePrint')->name('trial.balance.print');

    Route::get('report/sales', 'ReportController@salesReport')->name('report.sales');
    Route::post('print/sales-report', 'ReportController@salesReportPrint')->name('sales.report.print');

//    Route::get('report/receivables', 'ReportController@ReceivablesReport')->name('report.receivables');

    Route::post('journal-entry/setting/store', 'JournalEntryController@setting')->name('journal-entry.setting.store')->middleware(['auth']);








});

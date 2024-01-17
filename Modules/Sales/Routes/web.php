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

Route::group(['middleware' => 'PlanModuleCheck:Sales'], function ()
{
    Route::prefix('sales')->group(function() {
        Route::get('/', 'SalesController@index');
    });

    Route::get('dashboard/sales',['as' => 'sales.dashboard','uses' =>'SalesController@index'])->middleware(['auth']);

    // Contact
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::get('contact/grid', 'ContactController@grid')->name('contact.grid');
        Route::resource('contact', 'ContactController');
        Route::get('contact/create/{type}/{id}', 'ContactController@create')->name('contact.create');
    }
    );

     //Contact import
     Route::get('contact/import/export', 'ContactController@fileImportExport')->name('contact.file.import')->middleware(['auth']);
     Route::post('contact/import', 'ContactController@fileImport')->name('contact.import')->middleware(['auth']);
     Route::get('contact/import/modal', 'ContactController@fileImportModal')->name('contact.import.modal')->middleware(['auth']);
     Route::post('contact/data/import/', 'ContactController@contactImportdata')->name('contact.import.data')->middleware(['auth']);

    //Stream
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::post('streamstore/{type}/{id}/{title}', 'StreamController@streamstore')->name('streamstore');
        Route::resource('stream', 'StreamController');


    }
    );

    // Opportunities

    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::get('opportunities/grid', 'OpportunitiesController@grid')->name('opportunities.grid');
        Route::resource('opportunities', 'OpportunitiesController');
        Route::post('opportunities/change-order', 'OpportunitiesController@changeorder')->name('opportunities.change.order');
        Route::get('opportunities/create/{type}/{id}', 'OpportunitiesController@create')->name('opportunities.create');
    }
    );
    // Opportunities Stage
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::resource('opportunities_stage', 'OpportunitiesStageController');
    }
    );
    // Account
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::get('salesaccount/grid', 'SalesAccountController@grid')->name('salesaccount.grid');
        Route::resource('salesaccount', 'SalesAccountController');
        Route::get('salesaccount/create/{type}/{id}', 'SalesAccountController@create')->name('salesaccount.create');
    }
    );
    // Account Type
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::resource('account_type', 'SalesAccountTypeController');
    }
    );
    // account industry
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::resource('account_industry', 'SalesAccountIndustryController');
    }
    );

    //Sales Account import
    Route::get('salesaccount/import/export', 'SalesAccountController@fileImportExport')->name('salesaccount.file.import')->middleware(['auth']);
    Route::post('salesaccount/import', 'SalesAccountController@fileImport')->name('salesaccount.import')->middleware(['auth']);
    Route::get('salesaccount/import/modal', 'SalesAccountController@fileImportModal')->name('salesaccount.import.modal')->middleware(['auth']);
    Route::post('salesaccount/data/import/', 'SalesAccountController@salesaccountImportdata')->name('salesaccount.import.data')->middleware(['auth']);

    // sales document
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::get('salesdocument/grid', 'SalesDocumentController@grid')->name('salesdocument.grid');
        Route::resource('salesdocument', 'SalesDocumentController');
        Route::get('salesdocument/create/{type}/{id}', 'SalesDocumentController@create')->name('salesdocument.create');
    }
    );
    // sales document folder
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::resource('salesdocument_folder', 'SalesDocumentFolderController');
    }
    );
    // sales document type
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::resource('salesdocument_type', 'SalesDocumentTypeController');
    }
    );
    // Call
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::get('call/grid', 'CallController@grid')->name('call.grid');
        Route::post('call/getparent', 'CallController@getparent')->name('call.getparent');
        Route::resource('call', 'CallController');
        Route::get('call/create/{type}/{id}', 'CallController@create')->name('call.create');

    }
    );
    // Meeting
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::get('meeting/grid', 'MeetingController@grid')->name('meeting.grid');
        Route::post('meeting/getparent', 'MeetingController@getparent')->name('meeting.getparent');
        Route::resource('meeting', 'MeetingController');
        Route::get('meeting/create/{type}/{id}', 'MeetingController@create')->name('meeting.create');

    }
    );
    // Cases
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::get('commoncase/grid', 'CommonCaseController@grid')->name('commoncases.grid');
        Route::resource('commoncases', 'CommonCaseController');
        Route::get('commoncases/create/{type}/{id}', 'CommonCaseController@create')->name('commoncases.create');
    }
    );
    // case type
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::resource('case_type', 'CaseTypeController');
    }
    );
    Route::post('/setting/store', 'SalesController@setting')->name('sales.setting.store')->middleware(['auth']);
    // Quote
    Route::get('quote/preview/{template}/{color}', 'QuoteController@previewQuote')->name('quote.preview');
    Route::post('quote/template/setting', 'QuoteController@saveQuoteTemplateSettings')->name('quote.template.setting');
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){


        Route::get('quote/grid', 'QuoteController@grid')->name('quote.grid');
        Route::get('quote/{id}/convert', 'QuoteController@convert')->name('quote.convert');
        Route::post('quote/getaccount', 'QuoteController@getaccount')->name('quote.getaccount');
        Route::get('quote/quoteitem/{id}', 'QuoteController@quoteitem')->name('quote.quoteitem');
        Route::post('quote/storeitem/{id}', 'QuoteController@storeitem')->name('quote.storeitem');
        Route::get('quote/quoteitem/edit/{id}', 'QuoteController@quoteitemEdit')->name('quote.quoteitem.edit');
        Route::post('quote/storeitem/edit/{id}', 'QuoteController@quoteitemUpdate')->name('quote.quoteitem.update');
        Route::get('quote/items', 'QuoteController@items')->name('quote.items');
        Route::delete('quote/items/{id}/delete', 'QuoteController@itemsDestroy')->name('quote.items.delete');
        Route::resource('quote', 'QuoteController');
        Route::get('quote/create/{type}/{id}', 'QuoteController@create')->name('quote.create');

        Route::get('quote/{id}/duplicate', 'QuoteController@duplicate')->name('quote.duplicate');
        }
    );

    Route::get('quote/export', 'QuoteController@fileExport')->name('quote.export');

    // Shipping provider
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::resource('shipping_provider', 'ShippingProviderController');
    }
    );

    // Sales order
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::get('salesorder/grid', 'SalesOrderController@grid')->name('salesorder.grid');

        Route::get('salesorder/preview/{template}/{color}', 'SalesOrderController@previewSalesorder')->name('salesorder.preview');
        Route::post('salesorder/template/setting', 'SalesOrderController@saveSalesorderTemplateSettings')->name('salesorder.template.setting');


        Route::post('salesorder/getaccount', 'SalesOrderController@getaccount')->name('salesorder.getaccount');
        Route::get('salesorder/salesorderitem/{id}', 'SalesOrderController@salesorderitem')->name('salesorder.salesorderitem');
        Route::post('salesorder/storeitem/{id}', 'SalesOrderController@storeitem')->name('salesorder.storeitem');
        Route::get('salesorder/items', 'SalesOrderController@items')->name('salesorder.items');

        Route::get('salesorder/item/edit/{id}', 'SalesOrderController@salesorderItemEdit')->name('salesorder.item.edit');
        Route::post('salesorder/item/edit/{id}', 'SalesOrderController@salesorderItemUpdate')->name('salesorder.item.update');

        Route::delete('salesorder/items/{id}/delete', 'SalesOrderController@itemsDestroy')->name('salesorder.items.delete');
        Route::resource('salesorder', 'SalesOrderController');
        Route::get('salesorder/create/{type}/{id}', 'SalesOrderController@create')->name('salesorder.create');
        Route::get('salesorder/{id}/duplicate', 'SalesOrderController@duplicate')->name('salesorder.duplicate');

    }
    );

    // Sales Invocie
    Route::group(
        [
            'middleware' => [
                'auth',
            ],
        ], function (){
        Route::get('salesinvoice/grid', 'SalesInvoiceController@grid')->name('salesinvoice.grid');

        Route::get('salesinvoice/preview/{template}/{color}', 'SalesInvoiceController@previewInvoice')->name('salesinvoice.preview');
        Route::post('salesinvoice/template/setting', 'SalesInvoiceController@saveInvoiceTemplateSettings')->name('salesinvoice.template.setting');


        Route::post('salesinvoice/getaccount', 'SalesInvoiceController@getaccount')->name('salesinvoice.getaccount');
        Route::get('salesinvoice/invoiceitem/{id}', 'SalesInvoiceController@invoiceitem')->name('salesinvoice.invoiceitem');
        Route::post('salesinvoice/storeitem/{id}', 'SalesInvoiceController@storeitem')->name('salesinvoice.storeitem');
        Route::get('salesinvoice/items', 'SalesInvoiceController@items')->name('salesinvoice.items');
        Route::get('salesinvoice/item/edit/{id}', 'SalesInvoiceController@invoiceItemEdit')->name('salesinvoice.item.edit');
        Route::post('salesinvoice/item/edit/{id}', 'SalesInvoiceController@invoiceItemUpdate')->name('salesinvoice.item.update');
        Route::delete('salesinvoice/items/{id}/delete', 'SalesInvoiceController@itemsDestroy')->name('salesinvoice.items.delete');
        Route::resource('salesinvoice', 'SalesInvoiceController');
        Route::get('salesinvoice/create/{type}/{id}', 'SalesInvoiceController@create')->name('salesinvoice.create');

        Route::get('salesinvoice/{id}/duplicate', 'SalesInvoiceController@duplicate')->name('salesinvoice.duplicate');

        Route::get('salesinvoice/link/{id}', 'SalesInvoiceController@invoicelink')->name('salesinvoice.link');
        Route::post('salesinvoice/send/{id}', 'SalesInvoiceController@sendmail')->name('salesinvoice.sendmail');

        Route::get('invoices-payments', 'SalesInvoiceController@payments')->name('invoices.payments');
        Route::get('invoices/{id}/payments', 'SalesInvoiceController@paymentAdd')->name('invoices.payments.create');
        Route::post('invoices/{id}/payments', 'SalesInvoiceController@paymentStore')->name('invoices.payments.store');
    }
    );

    //Report
    Route::get('report/invoiceanalytic', 'ReportController@invoiceanalytic')->name('report.invoiceanalytic');
    Route::get('report/salesorderanalytic', 'ReportController@salesorderanalytic')->name('report.salesorderanalytic');
    Route::get('report/quoteanalytic', 'ReportController@quoteanalytic')->name('report.quoteanalytic');
});
Route::get('quote/pdf/{id}', 'QuoteController@pdf')->name('quote.pdf');
Route::get('/quote/pay/{quote}', ['as' => 'pay.quote','uses' => 'QuoteController@payquote']);

Route::get(
    '/salesinvoice/pay/{invoice}', [
        'as' => 'pay.salesinvoice',
        'uses' => 'SalesInvoiceController@payinvoice',
    ]
);
Route::get('salesinvoice/pdf/{id}', 'SalesInvoiceController@pdf')->name('salesinvoice.pdf');
Route::get(
    '/salesorder/pay/{salesorder}', [
        'as' => 'pay.salesorder',
        'uses' => 'SalesOrderController@paysalesorder',
    ]
);
Route::get('salesorder/pdf/{id}', 'SalesOrderController@pdf')->name('salesorder.pdf');


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

Route::group(['middleware' => 'PlanModuleCheck:Internalknowledge'], function () {
    Route::prefix('internalknowledge')->group(function () {
        Route::get('/', 'InternalknowledgeController@index');

        Route::group(
            [
                'middleware' => [
                    'auth',
                ],
            ],
            function () {
                Route::get('book/grid', 'BookController@grid')->name('book.grid');
                Route::resource('book', 'BookController');
            }
        );

        Route::group(
            [
                'middleware' => [
                    'auth',
                ],
            ],
            function () {
                Route::get('/article/copy/{id}', 'ArticleController@copyarticle')->name('article.copy');
                Route::post('/article/copy/store/{id}', 'ArticleController@copyarticlestore')->name('article.copy.store');
                Route::get('article/grid', 'ArticleController@grid')->name('article.grid');
                Route::resource('article', 'ArticleController');
                Route::get('/mindmap', 'ArticleController@mindmap')->name('mindmap');
                Route::get('article/mindmap/{id}', 'ArticleController@mindmapIndex')->name('mindmap.index');
                Route::post('/mindmap/{id}/{k}', 'ArticleController@mindmapSave')->name('mindmap.save');
                Route::post('/mindmap/store', 'ArticleController@mindmapStore')->name('mindmap.store');
                Route::any('/getmindmap/{id}/{k}', 'ArticleController@getMindmap')->name('mindmap.get');
                Route::put('/mindmap/update/', 'ArticleController@updateMindmap')->name('mindmap.update');
                Route::any('/mindmap/show/{id}', 'ArticleController@showMindmap')->name('mindmap.show');
            }
        );

        Route::group(
            [
                'middleware' => [
                    'auth',
                ],
            ],
            function () {
                Route::resource('myarticle', 'MyArticleController');
            }
        );
    });
});

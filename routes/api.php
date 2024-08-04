<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/v1/articles', [\App\Http\Controllers\API\V1\ArticleController::class, 'index'])
    ->name('api.articles.get');
Route::get('/v1/articles/{id}', [\App\Http\Controllers\API\V1\ArticleController::class, 'show'])
    ->name('api.article.get');

Route::group(['prefix' => 'v1/', 'namespace' => 'App\Http\Controllers\API\V1', 'middleware' => ['auth:sanctum']],
    function () {
        //Route::apiResource('articles', \App\Http\Controllers\API\V1\ArticleController::class);
        Route::post('articles', [\App\Http\Controllers\API\V1\ArticleController::class, 'store'])
            ->name('api.article.post');
        Route::put('articles/{id}', [\App\Http\Controllers\API\V1\ArticleController::class, 'update'])
            ->middleware('owner')
            ->name('api.article.put');
        Route::patch('articles/{id}', [\App\Http\Controllers\API\V1\ArticleController::class, 'update'])
            ->middleware('owner')
            ->name('api.article.patch');
        Route::delete('articles/{id}', [\App\Http\Controllers\API\V1\ArticleController::class, 'destroy'])
            ->middleware('owner')
            ->name('api.article.delete');
    }
);

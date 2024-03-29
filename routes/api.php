<?php

use App\Http\Controllers\Api\V1\ArticleController;
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
Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('login', [\App\Http\Controllers\Api\V1\AuthController::class, 'login']);
        Route::post('logout', [\App\Http\Controllers\Api\V1\AuthController::class, 'logout']);
        Route::post('refresh', [\App\Http\Controllers\Api\V1\AuthController::class, 'refresh']);
        Route::post('me', [\App\Http\Controllers\Api\V1\AuthController::class, 'me']);
    });
    Route::resource('articles', ArticleController::class);
    Route::post('upload', [ArticleController::class, 'upload']);
    Route::post('test', [ArticleController::class, 'test']);
    Route::get('cart', function () {
        return cart::get(2);
    });
    Route::post('cart/add/{article}', [\App\Http\Controllers\Api\V1\CartController::class, 'addToCart'])->middleware('api-session');
    Route::delete('cart/delete/{article}', [\App\Http\Controllers\Api\V1\CartController::class, 'deleteFromCart'])->middleware('api-session');


    Route::post('payment', [\App\Http\Controllers\Api\V1\PaymentController::class, 'payment'])->middleware('api-session');

});

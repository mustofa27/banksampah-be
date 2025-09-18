<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
});
Route::apiResource('news', NewsController::class)->only(['index', 'show']);
Route::apiResource('product', ProductController::class)->only(['index', 'show']);

Route::middleware('auth:api')->group(function () {
    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });
    Route::apiResource('news', NewsController::class)->except(['index','show','update']);
    Route::post('news/update', [NewsController::class, 'update']);
    Route::apiResource('product', ProductController::class)->except(['index','show','update']);
    Route::post('product/update', [ProductController::class, 'update']);
});

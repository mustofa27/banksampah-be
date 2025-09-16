<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;

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
    Route::post('refresh', [AuthController::class, 'refresh']);
});
Route::apiResource('news', NewsController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('me', [AuthController::class, 'me']);
    });
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });
    Route::apiResource('news', NewsController::class)->except(['index','show','update']);
    Route::post('news/update', [NewsController::class, 'update']);
});

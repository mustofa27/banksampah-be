<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GarbageController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BalanceController;

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
Route::apiResource('discount', DiscountController::class)->only(['index', 'show']);
Route::post('discount/product', [DiscountController::class, 'product_discount']);

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
    Route::apiResource('garbage', GarbageController::class)->except(['update']);
    Route::post('garbage/update', [GarbageController::class, 'update']);
    Route::apiResource('saving', SavingController::class)->except(['update']);
    Route::post('saving/update', [SavingController::class, 'update']);
    Route::post('saving/my', [SavingController::class, 'my']);
    Route::apiResource('discount', DiscountController::class)->except(['update','index','show']);
    Route::post('discount/update', [DiscountController::class, 'update']);
    Route::apiResource('cart', CartController::class)->except(['update','show']);
    Route::post('cart/my', [CartController::class, 'my']);
    Route::apiResource('transaction', TransactionController::class)->except(['update','show','destroy']);
    Route::post('transaction/my', [TransactionController::class, 'my']);
    Route::post('transaction/status', [TransactionController::class, 'update_status']);
    Route::post('balance/my', [BalanceController::class, 'my']);
});

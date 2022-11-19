<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UsersController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

/**
 * users crud routes
 */
Route::prefix('user')->middleware(['auth:sanctum', 'role:' . Role::ADMIN])->name('user.')->group(function () {
    Route::get('/', [UsersController::class, 'index'])->name('list');
    Route::post('/', [UsersController::class, 'store'])->name('store');
    Route::get('/{username}', [UsersController::class, 'show'])->name('show');
    Route::put('/{username}', [UsersController::class, 'update'])->name('update');
    Route::delete('/{username}', [UsersController::class, 'delete'])->name('delete');
});

/**
 * product crud routes
 */

Route::prefix('product')->name('product.')->group(function () {
    Route::get('/', [ProductsController::class, 'index'])->name('list');
    Route::get('/{id}', [ProductsController::class, 'show'])->name('show');
});

Route::prefix('product')->middleware(['auth:sanctum', 'role:' . Role::ADMIN])->name('product.')->group(function () {
    Route::post('/', [ProductsController::class, 'store'])->name('store');
    Route::get('/{id}', [ProductsController::class, 'show'])->name('show');
    Route::put('/{id}', [ProductsController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductsController::class, 'delete'])->name('delete');
});


/**
 * seller routes
 */

Route::prefix('seller')->middleware(['auth:sanctum', 'role:' . Role::SELLER])->name('seller.')->group(function () {
    Route::prefix('product')->middleware(['auth:sanctum', 'role:' . Role::SELLER])->name('product.')->group(function () {
        Route::get('/', [SellerController::class, 'listProduct'])->name('list');
        Route::post('/', [SellerController::class, 'storeProduct'])->name('store');
        Route::put('/{id}', [SellerController::class, 'updateProduct'])->name('update');
        Route::delete('/{id}', [SellerController::class, 'deleteProduct'])->name('delete');
    });
});


/**
 * buyer routes
 */


Route::post('/deposit', [BuyerController::class, 'deposit'])->middleware(['auth:sanctum', 'role:' . ROle::BUYER])->name('deposit');

Route::post('/buy', [BuyerController::class, 'buy'])->middleware(['auth:sanctum', 'role:' . ROle::BUYER])->name('buy');

Route::delete('/reset', [BuyerController::class, 'reset'])->middleware(['auth:sanctum', 'role:' . ROle::BUYER])->name('reset');
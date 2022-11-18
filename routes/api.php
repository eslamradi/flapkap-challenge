<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
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

 Route::get('product', [ProductsController::class, 'index'])->name('product.list');

Route::prefix('product')->middleware(['auth:sanctum', 'role:' . Role::ADMIN])->name('product.')->group(function () {
    Route::post('/', [ProductsController::class, 'store'])->name('store');
    Route::get('/{id}', [ProductsController::class, 'show'])->name('show');
    Route::put('/{id}', [ProductsController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductsController::class, 'delete'])->name('delete');
});

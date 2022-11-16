<?php

use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('user')->name('user.')->group(function () {
    Route::get('/', [UsersController::class, 'index'])->name('list');
    Route::post('/', [UsersController::class, 'store'])->name('store');
    Route::get('/{username}', [UsersController::class, 'show'])->name('show');
    Route::put('/{username}', [UsersController::class, 'update'])->name('update');
    Route::delete('/{username}', [UsersController::class, 'delete'])->name('delete');
});

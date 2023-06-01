<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
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

Route::post('/register', [UserController::class, "register"])->name('api.register');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, "login"])->name('api.auth.login');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/logout', [AuthController::class, "logout"])->name('api.auth.logout');
    });
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/dashboard/count-total-employee', [DashboardController::class, "countTotalEmployee"])->name('api.countTotalEmployee');
    Route::get('/dashboard/count-total-login', [DashboardController::class, "countTotalLogin"])->name('api.countTotalLogin');
    Route::get('/dashboard/count-total-unit', [DashboardController::class, "countTotalUnit"])->name('api.countTotalUnit');
    Route::get('/dashboard/count-total-position', [DashboardController::class, "countTotalPosition"])->name('api.countTotalPosition');
    Route::get('/dashboard/top-ten-user-by-login', [DashboardController::class, "topTenUserByLogin"])->name('api.topTenUserByLogin');
});

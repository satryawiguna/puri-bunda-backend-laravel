<?php

use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\UnitController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => '/position'], function () {
        Route::group(['prefix' => '/list'], function () {
            Route::get('/', [PositionController::class, 'list'])->name('api.position.list');
            Route::get('/search', [PositionController::class, 'listSearch'])->name('api.position.list.search');
            Route::get('/search/page', [PositionController::class, 'listSearchPage'])->name('api.position.list.search.page');
        });
        Route::get('/{id}', [PositionController::class, 'show'])->name('api.position.show');
        Route::post('/', [PositionController::class, 'store'])->name('api.position.create');
        Route::put('/{id}', [PositionController::class, 'update'])->name('api.position.update');
        Route::delete('/{id}', [PositionController::class, 'destroy'])->name('api.position.destroy');
    });

    Route::group(['prefix' => '/unit'], function () {
        Route::group(['prefix' => '/list'], function () {
            Route::get('/', [UnitController::class, 'list'])->name('api.unit.list');
            Route::get('/search', [UnitController::class, 'listSearch'])->name('api.unit.list.search');
            Route::get('/search/page', [UnitController::class, 'listSearchPage'])->name('api.unit.list.search.page');
        });
        Route::get('/{id}', [UnitController::class, 'show'])->name('api.unit.show');
        Route::post('/', [UnitController::class, 'store'])->name('api.unit.create');
        Route::put('/{id}', [UnitController::class, 'update'])->name('api.unit.update');
        Route::delete('/{id}', [UnitController::class, 'destroy'])->name('api.unit.destroy');
    });
});

<?php

use App\Http\Controllers\Api\PositionController;
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
});

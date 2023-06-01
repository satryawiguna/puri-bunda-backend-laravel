<?php

use App\Http\Controllers\Api\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => '/employee'], function () {
        Route::group(['prefix' => '/list'], function () {
            Route::get('/', [EmployeeController::class, 'list'])->name('api.employee.list');
            Route::get('/search', [EmployeeController::class, 'listSearch'])->name('api.employee.list.search');
            Route::get('/search/page', [EmployeeController::class, 'listSearchPage'])->name('api.employee.list.search.page');
            Route::get('/count', [EmployeeController::class, 'listCount'])->name('api.employee.list.count');
        });
        Route::get('/{id}', [EmployeeController::class, 'show'])->name('api.employee.show');
        Route::post('/', [EmployeeController::class, 'store'])->name('api.employee.create');
        Route::put('/{id}', [EmployeeController::class, 'update'])->name('api.employee.update');
        Route::delete('/{id}', [EmployeeController::class, 'destroy'])->name('api.employee.destroy');
    });
});

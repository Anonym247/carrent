<?php

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

Route::group(['prefix' => 'manage'], function () {
    Route::get('cars', [\App\Http\Controllers\ManagementController::class, 'cars']);
    Route::get('clients', [\App\Http\Controllers\ManagementController::class, 'clients']);
    Route::put('attach', [\App\Http\Controllers\ManagementController::class, 'attachCarToClient']);
    Route::put('detach', [\App\Http\Controllers\ManagementController::class, 'detachCarFromClient']);
});

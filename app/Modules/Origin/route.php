<?php

use App\Services\RolePermission;
use Origin\Http\Controllers\OriginController;

/**
 * API Routes
 */
Route::get('/getplans', [OriginController::class, "getOriginPlans"])->name('origin.plans.get');

Route::get('/productinfo', [OriginController::class, "getProductInfo"]);
Route::get('/validateaddress', [OriginController::class, "validateAddress"]);
Route::get('/checkfuel', [OriginController::class, "checkFuel"]);

Route::get('/submit', [OriginController::class, "submitOrder"]);

Route::get('/checkorder', [OriginController::class, "checkOrder"]);

Route::post('/leads', [OriginController::class, "store"]);
 
Route::prefix('/origin/api')->middleware(['api', 'auth:sanctum'])->group(function () {

});

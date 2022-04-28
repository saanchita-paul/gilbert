<?php

use App\Services\RolePermission;
use Origin\Http\Controllers\OriginController;

/**
 * API Routes
 */

Route::get('/productinfo', [OriginController::class, "getProductInfo"]);
Route::get('/validateaddress', [OriginController::class, "validateAddress"]);
Route::get('/checkfuel', [OriginController::class, "checkFuel"]);
 
Route::prefix('/origin/api')->middleware(['api', 'auth:sanctum'])->group(function () {

});

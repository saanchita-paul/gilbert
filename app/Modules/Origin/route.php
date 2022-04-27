<?php

use App\Services\RolePermission;
use Origin\Http\Controllers\OriginController;

/**
 * API Routes
 */

Route::get('/productInfo', [OriginController::class, "getProductInfo"]);
 
Route::prefix('/origin/api')->middleware(['api', 'auth:sanctum'])->group(function () {

});

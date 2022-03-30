<?php

use App\Services\RolePermission;
use PropertyMe\Http\Controllers\AuthController;
use PropertyMe\Http\Controllers\PropertyMeController;
use PropertyMe\PropertyMeLead;


Route::prefix('property-me')->group(function () {
    Route::get('/authorize', [AuthController::class, "authorizeWithCode"]);
});
Route::get("/property-me/callback", [AuthController::class, "callback"]);


/**
 * API Routes
 */
Route::prefix('/property-me/api')->middleware(['api', 'auth:sanctum'])->group(function () {
    Route::post('/leads', [PropertyMeController::class, "store"])
        ->middleware('permission:' . RolePermission::P_HOOD_ADMIN_CORE );
});


/**
 * This a test API to run a command.
 * this API maybe removed in the future.
 */
Route::get("/property-me/run-fetch-contacts", function () {
    $prevCount = PropertyMeLead::query()->count();
    Artisan::call('property_me:save_contact');

    sleep(5);
    $newLead = PropertyMeLead::query()->count() - $prevCount;
    return "<div style='display: flex; flex-direction: column; align-items: center'><h1 style='width: 100%; color: #480a8a; text-align: center;'>New <span style='color: #5C229A; font-weight: bold; font-size: 60px'>$newLead</span><span> lead is saved!</h1><img src='https://thumbs.gfycat.com/BraveSoftInexpectatumpleco-max-1mb.gif' />";
});

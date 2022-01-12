<?php

use PropertyMe\Http\Controllers\AuthController;
use PropertyMe\PropertyMeLead;
use PropertyMe\Services\AuthService;

Route::prefix('property-me')->group(function () {
    Route::get('/authorize', [AuthController::class, "authorizeWithCode"]);
});
Route::get("/property-me/callback", [AuthController::class, "callback"]);


/**
 * This a test API to run a command.
 * this API maybe removed in the future.
 */
Route::get("/property-me/run-fetch-contacts", function () {
    $prevCount = PropertyMeLead::query()->count();
    Artisan::call('property_me:save_contact');
    $newLead = $prevCount - PropertyMeLead::query()->count();

    return "New $newLead lead is saved!";
});

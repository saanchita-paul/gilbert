<?php

use PropertyMe\Http\Controllers\AuthController;
use PropertyMe\Services\AuthService;

Route::prefix('property-me')->group(function () {
    Route::get('/authorize', [AuthController::class, "authorizeWithCode"]);
});
Route::get("/property-me/callback", [AuthController::class, "callback"]);




<?php

use PropertyMe\Http\Controllers\AuthController;
use PropertyMe\Services\AuthService;

Route::prefix('property-me')->group(function () {
    Route::get('/authorize', [AuthController::class, "authorizeWithCode"]);
});
Route::get("/home/callback", [AuthController::class, "callback"]);


Route::get("/pp", function () {

    $url = '/okay?' . http_build_query(request()->toArray());
    return redirect($url)->withHeaders(request()->headers);
});


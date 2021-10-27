<?php

use Foxie\Http\Controllers\FoxieController;

Route::namespace('Foxie')->group(function () {
    Route::group(['middleware' => ['foxie.suger.leads']], function () {
            Route::post('/lead', [FoxieController::class, 'store']);
            Route::get('/lead', [FoxieController::class, 'show']);
            Route::patch('/lead/{id}', [FoxieController::class, 'update']);
    });
});
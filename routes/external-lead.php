<?php

use ExternalLead\Http\Controllers\ExternalLeadController;

Route::namespace('ExternalLead')->group(function () {
    Route::post('/t-app/token', [ExternalLeadController::class, 'getAccessToken']);
    Route::group(['middleware' => ['external.lead']], function () {
        Route::post('/create', [ExternalLeadController::class, 'createLeads']);
    });
});

<?php

use ExternalLead\Http\Controllers\ExternalLeadController;

Route::namespace('ExternalLead')->group(function () {
    Route::post('/token', [ExternalLeadController::class, 'getAccessToken']);
    Route::group(['middleware' => ['external.lead']], function () {
        Route::post('/leads', [ExternalLeadController::class, 'createLeads']);
    });
});

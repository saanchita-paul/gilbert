<?php

use ExternalLead\Http\Controllers\ExternalLeadV2Controller;

Route::namespace('ExternalLead')->group(function () {
    Route::post('/token', [ExternalLeadV2Controller::class, 'getAccessToken']);
    Route::group(['middleware' => ['external.lead']], function () {
        Route::post('/leads', [ExternalLeadV2Controller::class, 'createLeads']);
    });
});

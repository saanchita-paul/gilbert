<?php
use HoodLead\Http\Controllers\HoodLeadController;

Route::middleware(['api'])->group(function () {
    Route::post('leads', [HoodLeadController::class, "store"]);
});

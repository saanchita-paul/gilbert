<?php
use HoodLead\Http\Controllers\HoodLeadController;

Route::prefix('/api/hood-lead')->middleware(['api'])->group(function () {
    Route::post('leads', [HoodLeadController::class, "store"]);
    Route::post('save-lead', [HoodLeadController::class, "save"]);
});

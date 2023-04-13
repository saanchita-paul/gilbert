<?php
use App\Modules\NBN\Http\Controllers\NBNController;

/*
 * NBN
 */
Route::get('/applications', [NBNController::class, 'getNBNApplications']);
Route::get('/generate-caf', [NBNController::class, 'generateNbnCaf']);

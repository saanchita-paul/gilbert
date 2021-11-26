<?php


use OurProperty\Http\Controllers\OurPropertyController;

Route::post('/create', [OurPropertyController::class, 'createOurProperty']);

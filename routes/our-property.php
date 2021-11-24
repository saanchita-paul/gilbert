<?php


use OurProperty\Http\Controllers\OurPropertyController;

Route::get('/create', [OurPropertyController::class, 'createOurProperty']);

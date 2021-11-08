<?php

use Ignite\Http\Controllers\IgniteController;

Route::namespace('Ignite')->group(function () {
            Route::get('/lead', [IgniteController::class, 'show']);
});
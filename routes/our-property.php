<?php


use OurProperty\Http\Controllers\OurPropertyController;




Route::namespace('OurProperty')->group(function () {
    Route::group(['middleware' => ['our.property']], function () {
        Route::post('/create', [OurPropertyController::class, 'createOurProperty']);
    });
});

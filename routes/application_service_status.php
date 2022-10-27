<?php

use Illuminate\Support\Facades\Route;

Route::post('application-service-statuses/change-status', "ApplicationServiceStatusController@changeStatus");
Route::apiResource('application-service-statuses', "ApplicationServiceStatusController");

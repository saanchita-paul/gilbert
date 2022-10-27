<?php

use Illuminate\Support\Facades\Route;

Route::post('application-service-statuses/change-status', "ApplicationServiceStatusController@changeStatus");
Route::post('application-service-statuses/change-bulk-status', "ApplicationServiceStatusController@changeBulkStatus");
Route::apiResource('application-service-statuses', "ApplicationServiceStatusController");

<?php

use Illuminate\Support\Facades\Route;

// Application Service status change routes
Route::post('application-service-statuses/change-status', "ApplicationServiceStatusController@changeStatus");
Route::post('application-service-statuses/change-bulk-status', "ApplicationServiceStatusController@changeBulkStatus");

// Application service status crud routes
Route::apiResource('application-service-statuses', "ApplicationServiceStatusController");

// Application service status change logs routes
Route::get('manual-status-change-logs', "ManualStatusChangeLogsController@index");
Route::get('manual-status-change-logs/{id}', "ManualStatusChangeLogsController@statusLogsByApplicationId");

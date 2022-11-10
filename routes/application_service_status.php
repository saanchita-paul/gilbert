<?php

use App\Services\RolePermissionService;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->middleware('permission:' . RolePermissionService::CAN_GET_APPLICATION_DETAILS)
    ->middleware('permission:' . RolePermissionService::CAN_CHANGE_MANUAL_STATUS)
    ->group(function () {
        // Application Service status change routes
        Route::post(
            'application-service-statuses/change-status',
            "ApplicationServiceStatusController@changeStatus"
        );
        Route::post(
            'application-service-statuses/change-bulk-status',
            "ApplicationServiceStatusController@changeBulkStatus"
        );
        Route::post(
            'application-service-statuses/get-service-status-dd',
            "ApplicationServiceStatusController@getServiceStatusDD"
        );
        Route::get(
            'application-service-statuses/get-water-service-status-dd',
            "ApplicationServiceStatusController@getWaterServiceStatusDD"
        );

        // Application service status crud routes
        Route::apiResource(
            'application-service-statuses',
            "ApplicationServiceStatusController"
        );

        // Application service status change logs routes
        Route::get(
            'applications/{application_id}/manual-status-change-logs',
            "ManualStatusChangeLogsController@statusLogsByApplicationId"
        );
    });

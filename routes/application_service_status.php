<?php

use App\Services\RolePermissionService;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->middleware('permission:' . RolePermissionService::CAN_GET_APPLICATION_DETAILS)
    ->middleware('permission:' . RolePermissionService::CAN_CHANGE_MANUAL_STATUS)
    ->group(function () {
        // Application Service status change routes
        Route::post(
            'applications/{connectionApplication}/change-status',
            "ApplicationServiceStatusController@changeStatus"
        );

        Route::post(
            'application-service-statuses/get-service-status',
            "ApplicationServiceStatusController@getServiceStatus"
        );
        Route::post(
            'application-service-statuses/get-water-service-status',
            "ApplicationServiceStatusController@getWaterServiceStatus"
        );
        Route::post(
            'application-service-statuses/get-internet-service-status',
            "ApplicationServiceStatusController@getInternetServiceStatus"
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

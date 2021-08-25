<?php

use App\Http\Controllers\Agency\AgencyController;
use App\Http\Controllers\Agency\AgentProfileController;
use App\Http\Controllers\Agency\HoodUserController;
use App\Http\Controllers\Agency\OfficeController;
use App\Http\Controllers\Agency\ApplicationController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::middleware('auth:sanctum')
    ->get('/user', [AuthController::class, 'authUser']);

Route::get('/logout', [AuthController::class, 'logout']);

/**
 * @Module AGENCY CRM
 */
Route::namespace('agency')->middleware(['auth:sanctum'])->group(function () {
//Route::namespace('agency')->middleware([])->group(function () {
    /**
     * Agency, Office Users
     */
    Route::get('/agencies', [AgencyController::class, 'index']);
    Route::post('/agencies', [AgencyController::class, 'create']);
    Route::get('/agencies/{id}', [AgencyController::class, 'getAgency']);
    Route::post('/agencies/{id}/update', [AgencyController::class, 'update']);
    Route::get('/agencies/{agencyId}/offices', [OfficeController::class, 'index']);
    Route::post('/agencies/{agencyId}/offices', [OfficeController::class, 'createAgencyOffice']);

    Route::post('/independent-agency', [AgencyController::class, 'createIndependentAgency']);

    Route::post('/offices', [OfficeController::class, 'createOffice']);
    Route::get('/offices/{id}', [OfficeController::class, 'getOffice']);
    Route::get('/offices/office/{id}', [OfficeController::class, 'getOnlyOffice']);
    Route::post('/offices/{id}/update', [OfficeController::class, 'updateOffice']);
    Route::get('/offices/{officeId}/users', [AgentProfileController::class, 'index']);
    Route::post('/offices/{officeId}/users', [AgentProfileController::class, 'createAgent']);

    Route::get('/office-agents', [AgentProfileController::class, 'officeAgents']);
    Route::post('/office-agents/{id}/update', [AgentProfileController::class, 'updateProfile']);
    Route::post('/office-agents/{id}', [AgentProfileController::class, 'getAgent']);

    /**
     * Hood User
     */
    Route::get('/application-assignees', [HoodUserController::class, 'getAssignee']);
    Route::post('/hood-users', [HoodUserController::class, 'store']);

    /**
     * Applications
     */
    Route::post('/applications', [ApplicationController::class, 'create']);
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/applications/{application}', [ApplicationController::class, 'view']);

    Route::post('/applications/{id}/submit', [ApplicationController::class, 'submit']);
    Route::post('/applications/{applicationId}/assign', [ApplicationController::class, 'assignUser']);
    Route::post('/applications/{applicationId}/escalate', [ApplicationController::class, 'escalate']);

    //todo: make a  separate controller for notes
    Route::get('/applications/{id}/notes', [ApplicationController::class, 'getConnectionNotes']);
    Route::post('/applications/{id}/notes', [ApplicationController::class, 'createConnectionNotes']);

    Route::get('/applications-metrics', [ApplicationController::class, 'getMetrics']);
});

/**
 * test routes
 */
Route::get('lnn/bot_token', function () {
    return (new Encrypter(config('bot.encryption_key')))->decrypt(\request()->get('bot_token'), true);
});

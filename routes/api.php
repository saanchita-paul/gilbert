<?php

use App\Http\Controllers\Agency\AgencyController;
use App\Http\Controllers\Agency\AgentProfileController;
use App\Http\Controllers\Agency\OfficeController;
use App\Http\Controllers\Agent\ApplicationController;
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
//Route::namespace('agency')->middleware(['auth:sanctum'])->group(function () {
Route::namespace('agency')->middleware([])->group(function () {
    Route::get('/agencies', [AgencyController::class, 'index']);
    Route::get('/agencies/{agencyId}/offices', [OfficeController::class, 'index']);
    Route::post('/agency', [AgencyController::class, 'create']);
    Route::post('/office', [OfficeController::class, 'createOffice']);
    Route::get('/offices/{officeId}/users', [AgentProfileController::class, 'index']);
    Route::post('/agent', [AgentProfileController::class, 'createAgent']);
    Route::post('/application', [ApplicationController::class, 'create']);
    Route::get('/application', [ApplicationController::class, 'index']);
    Route::post('/applications/{applicationId}/assignee', [ApplicationController::class, 'updateAssignee']);
    Route::get('/application/summary/{application}', [ApplicationController::class, 'summary']);
});

/**
 * test routes
 */
Route::get('lnn/bot_token', function () {
    return (new Encrypter(config('bot.encryption_key')))->decrypt(\request()->get('bot_token'), true);
});

<?php

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Route;
use PropertyMe\services\FetchContacts;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Agency\NoteController;
use Reporting\Http\Controllers\ReportController;
use App\Http\Controllers\Agency\AgencyController;
use App\Http\Controllers\Agency\OfficeController;
use App\Http\Controllers\UserInvitationController;
use App\Http\Controllers\Agency\HoodUserController;
use App\Http\Controllers\Agency\ApplicationController;
use FastConnect\Services\SubmitWaterLeadToFastConnect;
use App\Http\Controllers\Agency\AgentProfileController;
use OurProperty\Http\Controllers\OurPropertyController;

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
    // Route::namespace('agency')->middleware([])->group(function () {
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
    Route::get('/offices/{id}/get-metrics', [OfficeController::class, 'getMatricsData']);
    Route::post('/offices/{id}/update', [OfficeController::class, 'updateOffice']);
    Route::get('/offices/{officeId}/users', [AgentProfileController::class, 'index']);
    Route::post('/offices/{officeId}/users', [AgentProfileController::class, 'createAgent']);

    Route::get('/office-agents', [AgentProfileController::class, 'officeAgents']);
    Route::post('/office-agents/{id}/update', [AgentProfileController::class, 'updateProfile']);

    Route::post('/office-agents/{id}/update-user-data', [AgentProfileController::class, 'updateUserData']);
    Route::post('/office-agents/{id}/send-confirm-mail', [AgentProfileController::class, 'sendConfirmMail']);

    Route::post('/office-agents/{id}', [AgentProfileController::class, 'getAgent']);

    /**
     * Hood User
     */
    Route::get('/application-assignees', [HoodUserController::class, 'getAssignee']);
    Route::post('/hood-users', [HoodUserController::class, 'store']);
    Route::get('/hood-users', [HoodUserController::class, 'index']);

    /**
     * Applications
     */
    Route::post('/applications', [ApplicationController::class, 'create']);
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/applications/{application}', [ApplicationController::class, 'view']);

    Route::post('/applications/{id}/submit', [ApplicationController::class, 'submit']);
    Route::post('/applications/{applicationId}/assign', [ApplicationController::class, 'assignUser']);
    Route::post('/applications/{applicationId}/escalate', [ApplicationController::class, 'escalate']);
    Route::post('/applications/{applicationId}/closeApplication', [ApplicationController::class, 'closeApplication']);
    Route::put('/applications/{applicationId}/update-address', [ApplicationController::class, 'updateAddress']);
    Route::post('/applications/{applicationId}/draft', [ApplicationController::class, 'saveDraft']);
    Route::put('/applications/{id}/close', [ApplicationController::class, 'close']);
    Route::patch('/applications/{applicationId}/providers', [ApplicationController::class, 'providers']);

    //todo: make a  separate controller for notes
    Route::get('/applications/{id}/notes', [NoteController::class, 'getConnectionNotes']);
    Route::post('/applications/{id}/notes', [NoteController::class, 'createConnectionNotes']);

    Route::get('/applications-metrics', [ApplicationController::class, 'getMetrics']);
    Route::get('/applications-metrics-count', [ApplicationController::class, 'getApplicationMetricsCount']);
    Route::get('/applications/{id}/nmi-mern', [ApplicationController::class, 'getNmiMern']);
    Route::get('/authoized-person/{id}', [ApplicationController::class, 'getAuthorizedPerson']);
    Route::post('/authoized-person', [ApplicationController::class, 'updateAuthorizedPerson']);

    Route::post('/applications/{application_id}/service/update', [ApplicationController::class, 'updateService']);

    //'+id
});

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::post('/invitation/validation', [UserInvitationController::class, 'validateInvitation']);
Route::post('/invitation/change-password', [UserInvitationController::class, 'passwordChange']);

Route::post('/register/email-validation', [AuthController::class, 'isValidUser']);
Route::get('/users/is-unique-email', [AuthController::class, 'isEmailValid']);
Route::get('/users/is-unique-email-update', [AuthController::class, 'isEmailTaken']);

Route::get('/{id}/submit-water-lead', [ApplicationController::class, 'submitWaterLead']);


/***
 * Sales Dashboard
 */
Route::get('/sales-dashboard/home', [ReportController::class, 'home']);
Route::get('/sales-dashboard/export/submission-report', [ReportController::class, 'submissionReport']);
Route::get('/plans-details/{id}/export', [NoteController::class, 'download']);




/**
 * test routes
 */
Route::get('lnn/bot_token', function () {
    return (new Encrypter(config('bot.encryption_key')))->decrypt(\request()->get('bot_token'), true);
});




Route::post('/our-property/token', [OurPropertyController::class, 'getAccessToken']);
Route::post('/our-property/lead', [OurPropertyController::class, 'createOurProperty']);





Route::get("/karan/sales-status", function () {
    $id = request()->get('id');
    $power = request()->get('power');
    $gas = request()->get('gas');

    $ap = \App\Models\ConnectionApplication::findOrFail($id);
    foreach ($ap->connectionServices as $service) {
            if ($power === 'accepted' && $service->service_type === 'power') {
                $service->status = \App\Models\ConnectionService::STATUS_ACCEPTED;
            }
            if ($power === 'rejected' && $service->service_type === 'power') {
                $service->lead_reference = null;
                $service->status = \App\Models\ConnectionService::STATUS_REJECTED;
            }

            if ($gas === 'accepted'  && $service->service_type === 'gas') {
                $service->status = \App\Models\ConnectionService::STATUS_ACCEPTED;
            }
            if ($gas === 'rejected' && $service->service_type === 'gas') {
                $service->lead_reference = null;
                $service->status = \App\Models\ConnectionService::STATUS_REJECTED;
            }
            $service->save();
        }
    return "success";
});



Route::get('country_test', function () {
    $ser =  new SubmitWaterLeadToFastConnect(1);
    return $ser->getMappedIdentificationCountry('Australia'); 
    // return 'got' ;
});
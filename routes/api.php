<?php

use App\Models\ConnectionApplication;
use App\Http\Controllers\Agency\AppCloseReasonController;
use App\Services\Agency\TriageFlagService;
use Illuminate\Encryption\Encrypter;
use App\Services\Address\GBGServices;
use Illuminate\Support\Facades\Route;
use App\Services\Address\AddressModel;
use PropertyMe\services\FetchContacts;
use App\Services\RolePermissionService;
use TSA\Services\TsaCallHistoryService;
use Illuminate\Support\Facades\Broadcast;
use TSA\Services\TsaSendAppliationService;
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
use App\Services\RolePermission;
use App\Http\Controllers\Agency\ReaExtractsReportController;

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
//     Route::namespace('agency')->middleware([])->group(function () {
    /**
     * Agency, Office Users
     */
    Route::get('/agencies', [AgencyController::class, 'index'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_AGENCY_LIST );
    Route::post('/agencies', [AgencyController::class, 'create'])
        ->middleware('permission:' . RolePermissionService::CAN_CREATE_FRANCHISED_AGENCY );
    Route::get('/agencies/get-agency-metrics', [AgencyController::class, 'getAgencyMetrics']); # not is use
    Route::get('/agencies/get-agency-application-metrics', [AgencyController::class, 'getAgencyApplicationMetrics'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_APPLICATION_METRICS);
    Route::get('/agencies/{id}', [AgencyController::class, 'getAgency'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_AGENCY_DETAILS);
    Route::post('/agencies/{id}/update', [AgencyController::class, 'update'])
        ->middleware('permission:' . RolePermissionService::CAN_UPDATE_AGENCY);
    Route::get('/agencies/{agencyId}/offices', [OfficeController::class, 'index'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_OFFICES);
    Route::post('/agencies/{agencyId}/offices', [OfficeController::class, 'createAgencyOffice']);

    Route::post('/independent-agency', [AgencyController::class, 'createIndependentAgency'])
        ->middleware('permission:' . RolePermissionService::CAN_CREATE_INDEPENDENT_AGENCY);

    Route::post('/offices', [OfficeController::class, 'createOffice'])
        ->middleware('permission:' . RolePermissionService::CAN_CREATE_NEW_OFFICE );
    Route::get('/offices/{id}', [OfficeController::class, 'getOffice'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_OFFICE_DETAILS);
    Route::get('/offices/office/{id}', [OfficeController::class, 'getOnlyOffice'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_OFFICE_DETAILS);
    Route::get('/offices/{id}/get-metrics', [OfficeController::class, 'getMatricsData'])
         ->middleware('permission:' . RolePermissionService::CAN_GET_OFFICE_METRICS);
    Route::post('/offices/{id}/update', [OfficeController::class, 'updateOffice'])
        ->middleware('permission:' . RolePermissionService::CAN_UPDATE_OFFICE);
    Route::get('/offices/{officeId}/users', [AgentProfileController::class, 'index'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_APPLICATION_LIST);
    Route::get('/offices/{officeId}/agents', [AgentProfileController::class, 'getAgentList'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_AGENT_LIST);
    Route::post('/offices/{officeId}/users', [AgentProfileController::class, 'createAgent'])
        ->middleware('permission:' . RolePermissionService::CAN_CREATE_OFFICE_USER);

    Route::get('/office-agents', [AgentProfileController::class, 'officeAgents']);
    Route::post('/office-agents/{id}/update', [AgentProfileController::class, 'updateProfile'])
        ->middleware('permission:' . RolePermissionService::CAN_UPDATE_AGENT_PROFILE);

    Route::post('/office-agents/{id}/update-user-data', [AgentProfileController::class, 'updateUserData'])
        ->middleware('permission:' . RolePermissionService::CAN_UPDATE_OFFICE_AGENT);
    Route::post('/office-agents/{id}/send-confirm-mail', [AgentProfileController::class, 'sendConfirmMail'])
        ->middleware('permission:' . RolePermissionService::CAN_SEND_CONFIRRMATION_MAIL);

    Route::post('/office-agents/{id}', [AgentProfileController::class, 'getAgent']);

    Route::get('/alloffices', [OfficeController::class, 'allOffices'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_OFFICES);

    /**
     * Hood User
     */
    Route::get('/application-assignees', [HoodUserController::class, 'getAssignee'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_ASSIGNEE_LIST);

    // Route::post('/hood-users', [HoodUserController::class, 'store']);

    Route::post('/hood-users', [HoodUserController::class, 'store'])
        ->middleware('permission:' . RolePermission::P_HOOD_ADMIN_CORE);

    Route::get('/hood-users', [HoodUserController::class, 'index'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_OFFICES);

    Route::get('/users/is-unique-email', [AuthController::class, 'isEmailValid']);
    Route::get('/users/is-unique-email-update', [AuthController::class, 'isEmailTaken'])
        ->middleware('permission:' . RolePermissionService::CAN_UPDATE_OFFICE_AGENT);

    /**
     * Applications
     */
    Route::post('/applications', [ApplicationController::class, 'create'])
        ->middleware('permission:' . RolePermissionService::CAN_CREATE_NEW_APPLICATION);
    Route::get('/applications', [ApplicationController::class, 'index'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_APPLICATION_LIST);
    Route::get('/applications/agents', [ApplicationController::class, 'SearchConnectionApplicationAgents']);
    Route::get('/applications/{application}', [ApplicationController::class, 'view'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_APPLICATION_DETAILS);
    Route::post('/applications/{id}/submit', [ApplicationController::class, 'submit'])
        ->middleware('permission:' . RolePermissionService::CAN_SUBMIT_APPLICATION);
    Route::post('/applications/{applicationId}/assign', [ApplicationController::class, 'assignUser'])
        ->middleware('permission:' . RolePermissionService::CAN_ASSIGN_HOOD_USER);
    Route::post('/applications/{applicationId}/escalate', [ApplicationController::class, 'escalate'])
        ->middleware('permission:' . RolePermissionService::CAN_ESCALATE_APPLICATION);
    Route::post('/applications/{applicationId}/closeApplication', [ApplicationController::class, 'closeApplication'])
        ->middleware('permission:' . RolePermissionService::CAN_CLOSE_APPLICATION);
    Route::put('/applications/{applicationId}/update-address', [ApplicationController::class, 'updateAddress'])
        ->middleware('permission:' . RolePermissionService::CAN_UPDATE_ADDRESS);
    Route::post('/applications/{applicationId}/draft', [ApplicationController::class, 'saveDraft'])
        ->middleware('permission:' . RolePermissionService::CAN_UPDATE_APPLICATION);
    Route::put('/applications/{id}/close', [ApplicationController::class, 'close']);
    Route::patch('/applications/{applicationId}/providers', [ApplicationController::class, 'providers'])
        ->middleware('permission:' . RolePermissionService::CAN_UPDATE_SERVICE_PROVIDERS);
    Route::post('/applications/{applicationId}/clear-concession-details', [ApplicationController::class, 'clearConcession'])
        ->middleware('permission:' . RolePermissionService::CAN_UPDATE_APPLICATION);

    //todo: make a  separate controller for notes
    Route::get('/applications/{id}/notes', [NoteController::class, 'getConnectionNotes'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_APPLICATION_NOTES);
    Route::post('/applications/{id}/notes', [NoteController::class, 'createConnectionNotes'])
        ->middleware('permission:' . RolePermissionService::CAN_CREATE_NOTES);

    Route::get('/applications-metrics', [ApplicationController::class, 'getMetrics'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_LEAD_METRICS);
    Route::get('/applications-metrics-count', [ApplicationController::class, 'getApplicationMetricsCount'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_APPLICATION_METRICS);
    Route::get('/applications/{id}/nmi-mern', [ApplicationController::class, 'getNmiMern'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_NMI_MERN);
    Route::get('/secondary-contact/{id}', [ApplicationController::class, 'getAuthorizedPerson'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_AUTHORIZED_PERSON);
    Route::post('/secondary-contact', [ApplicationController::class, 'updateAuthorizedPerson'])
        ->middleware('permission:' . RolePermissionService::CAN_SAVE_AUTHORIZED_PERSON);

    Route::post('/applications/{application_id}/service/update', [ApplicationController::class, 'updateService']);
    Route::get('/applications/{id}/get-assigned-hood-user', [ApplicationController::class, 'getAssignedHoodUser'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_ASSIGNED_USER);

    //'+id

    /***
        * Sales Dashboard
    */
    Route::get('/sales-dashboard/home', [ReportController::class, 'home'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_OPERATION_REPORT);
    Route::get('/sales-dashboard/export/submission-report', [ReportController::class, 'submissionReport'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_EXPORT_REPORT);
    Route::get('/plans-details/{id}/export', [NoteController::class, 'download']);

    // REA extracts report
    Route::get('/rea-extract/report', [ReaExtractsReportController::class, 'getReaReport'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_APPLICATION_LIST);

    /***
        * Application closing reasons route
    */
    // application closing reasons list
    Route::get('/app-close-reasons', [AppCloseReasonController::class, 'index']);
    // application closing reasons create
    Route::post('/app-close-reasons', [AppCloseReasonController::class, 'create']);
    // application closing reasons show
    Route::get('/app-close-reasons/{id}', [AppCloseReasonController::class, 'show']);
    // application closing reasons update
    Route::put('/app-close-reasons/{id}', [AppCloseReasonController::class, 'update']);
    // application closing reasons delete
    Route::delete('/app-close-reasons/{id}', [AppCloseReasonController::class, 'delete']);


    Route::get('/rea-extract/corporate-report', [ReaExtractsReportController::class, 'getReaCorporateReport'])
        ->middleware('permission:' . RolePermissionService::CAN_GET_APPLICATION_LIST);

});

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/check-is-valid-token', [AuthController::class, 'checkIsValidToken']);

Route::post('/invitation/validation', [UserInvitationController::class, 'validateInvitation']);
Route::post('/invitation/change-password', [UserInvitationController::class, 'passwordChange']);

Route::post('/register/email-validation', [AuthController::class, 'isValidUser']);

Route::get('/{id}/submit-water-lead', [ApplicationController::class, 'submitWaterLead']);

/**
 * api to get the uuid for sumo
 */
Route::get('/sumo/generate-uuid/{id}', [ApplicationController::class, 'getSumoUuid']);



/**
 * api's for admin only
 */
Route::get('/get-report-access-token', [ReportController::class, 'getReportAccessToken']);



/**
 * test routes
 */
Route::get('lnn/bot_token', function () {
    return (new Encrypter(config('bot.encryption_key')))->decrypt(\request()->get('bot_token'), true);
});



Route::get('/applications/{id}/validate-cutoff/', [ApplicationController::class, 'validateCutOff']);


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




// Route::get('report_corporate', function () {
//     $data = ['image' => ''];
//     $pdf = PDF::loadView('pdf.report_corporate', $data);
//     return $pdf->inline();
// });


Route::get('country_test', function () {
    //  return SubmitWaterLeadToFastConnect::mapLengthOfCountry[2];
    $s = new TsaCallHistoryService();
    // ConnectionApplication::find(12)
    // $s->saveCallHistory(ConnectionApplication::find(12));
    $s->saveCallHistory(ConnectionApplication::find(12));
});




Route::get('/kaka', function () {
    $array = [
//        'GenerateRequest' => [
            'PxPayUserId' => 'PowershopSignUp_Dev',
            'PxPayKey' => 'f598f2c0e3199578c499a778f33657b601f24f361af110fe0a22a542e5a1a657',
//            'TxnType' => 'Purchase',
            'TxnType' => 'Validate',
//            'AmountInput' => 0.0,
            'CurrencyInput' => 'AUD',
            'MerchantReference' => 'Purchase Example',
            'TxnData1' => 'Atikur Rahman',
            'TxnData12' => '0211111111',
            'EmailAddress' => 'a@gmail.com',
//            'TxnId' => 'hoodlenin002',
            'TxnId' => Str::random(10),
            'BillingId' => '0000070082450801',
            'EnableAddBillCard' => 1,
            'RecurringMode' => 'recurringinitial',
            'UrlSuccess' => 'https://enk.leninsheikh.com/api/kaka/success',
            'UrlFail' => 'https://enk.leninsheikh.com/api/kaka/failed',
            'UrlCallback' => 'https://enk.leninsheikh.com/api/kaka/callback',
//        ]
    ];

//    $xml = ArrayToXml::convert($array);
    $xml = toXml($array, 'GenerateRequest');

    $client = new \GuzzleHttp\Client();

    $options = [
        'headers' => [
            'Content-Type' => 'text/xml; charset=UTF8',
        ],
        'body' => $xml,
    ];

    $response = $client->request('POST', 'https://sec.windcave.com/pxaccess/pxpay.aspx', $options);

    $body = $response->getBody()->getContents();
    $uri = (string ) simplexml_load_string($body)->URI ?? null;
    if (!empty($uri)) {
        return redirect($uri);
    } else {
        dd($body);
    }
});
Route::any('/kaka/failed', function () {
    dump('failed', request()->all(), request()->method());
});
Route::any('/kaka/success', function () {
    $ref = request()->get('result');
    $details = getDetails($ref);
    return response()->json($details);
});

Route::any('/kaka/callback', function () {
    dump('success', request()->all(), request()->method());
});

Route::any('/kaka/details', function () {

    $key = request()->get('key');
    if (!$key) {
        dd('key is missing');
    }

    $details = getDetails($key);
    return response()->json($details);
});

function getDetails(string $key) {
    $array = [
        'PxPayUserId' => 'PowershopSignUp_Dev',
        'PxPayKey' => 'f598f2c0e3199578c499a778f33657b601f24f361af110fe0a22a542e5a1a657',
        'Response' => $key,
    ];

    $xml = toXml($array, 'ProcessResponse');
    $options = [
        'headers' => [
            'Content-Type' => 'text/xml; charset=UTF8',
        ],
        'body' => $xml,
    ];
    $client = new \GuzzleHttp\Client();

    $response = $client->request('POST', 'https://sec.windcave.com/pxaccess/pxpay.aspx', $options);
    $body = $response->getBody()->getContents();
    $res = json_decode(json_encode(simplexml_load_string($body)), true);

    return ["payment_details" => [
        "card" => [
            "card_type" => data_get($res, 'CardName'),
            "masked_card_number" => data_get($res, 'CardNumber'),
            "expiry_date" => data_get($res, 'DateExpiry'),
            "cardholder_name" => data_get($res, 'CardHolderName'),
            "token" => data_get($res, 'DpsBillingId'),
            "terms_and_conditions_accepted_at" => now(\App\Services\TimeZoneService::getTimeZoneInt()),
            "preferred" => true
        ]
    ]];
}

function toXml($array, $root) {

    $xml  = "<$root>";
    foreach ($array as $key => $value) {
        $xml .= "<$key>$value</$key>" ;
    }
//    while (list($prop, $val) = each($arr))
//        $xml .= "<$prop>$val</$prop>" ;

    $xml .= "</$root>";
    return $xml;
}

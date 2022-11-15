<?php

use App\Http\Controllers\Auth\AuthController;
use App\Services\Agency\ApplicationService;
use App\Services\FastConnectService;
use Powershop\Http\Controllers\PxPayController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/hello', [\App\Http\Controllers\TestControler::class, 'index']);

Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::get('/powershop/payment/accept-invite/{id}', [PxPayController::class, 'acceptInvite']);

Route::get('/powershop/payment/success', [PxPayController::class, 'handleSuccess']);
Route::get('/powershop/payment/failed', [PxPayController::class, 'handleFailure']);

Route::get('/powershop/payment/callback', [PxPayController::class, 'handleCallback']);

Route::get('/email', function () {
    return response('hello world');
});

Route::get('mi-test', function () {
    $inputData = [
        "additional_access_information" => null,
        "address_text" => "7 WODONGA CRES, THOMASTOWN VIC 3074",
        "billing_address" => "",
        "billing_address_text" => "7 WODONGA CRES, THOMASTOWN VIC 3074",
        "billing_city" => "THOMASTOWN",
        "billing_country" => "",
        "billing_postcode" => "3074",
        "billing_state" => "Victoria",
        "billing_street_address" => "7 WODONGA",
        "billing_street_name" => null,
        "billing_street_name_only" => "WODONGA",
        "billing_street_number" => "7",
        "billing_street_type" => "CRES",
        "billing_unit_number" => null,
        "city" => "THOMASTOWN",
        "connection_end_date" => null,
        "country" => "Australia",
        "has_electricity" => 1,
        "has_life_support" => null,
        "has_solar" => 1,
        "inspection_time" => null,
        "is_access_require" => null,
        "is_any_unrestrained_animal" => null,
        "is_billing_same" => 1,
        "is_gas_life_support" => null,
        "is_power_life_support" => null,
        "is_renovation_on" => 0,
        "is_temporary_connection" => null,
        "life_support" => "",
        "mirn" => null,
        "moving_date" => "18/11/2022",
        "nmi" => null,
        "postcode" => "3074",
        "property_type" => 1,
        "solor_power" => "",
        "state" => "Victoria",
        "street_address" => "7 WODONGA",
        "street_name" => "WODONGA",
        "street_name_only" => "WODONGA",
        "street_number" => "7",
        "street_type" => "CRES",
        "unit_number" => null,
    ];
    $svcUtilities = new FastConnectService();
    $result = $svcUtilities->authenticate()->searchAddress($inputData);
    dd($result);
    $service = new ApplicationService();
});

Route::get('/{vue_capture?}', fn() => view('app'))
    ->where('vue_capture', '[\/\w\.-]*');

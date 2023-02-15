<?php

use App\Http\Controllers\Auth\AuthController;
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

Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::get('/powershop/payment/accept-invite/{id}', [PxPayController::class, 'acceptInvite']);

Route::get('/powershop/payment/success', [PxPayController::class, 'handleSuccess']);
Route::get('/powershop/payment/failed', [PxPayController::class, 'handleFailure']);

Route::get('/powershop/payment/callback', [PxPayController::class, 'handleCallback']);

Route::get('/email', function () {
    return response('hello world');
});

Route::get('mi-test', function () {
    $app = \App\Models\ConnectionApplication::find(5583);
    $paymentData = $app->powershopPaymentInfo ? collect($app->powershopPaymentInfo->getAttributes()) : collect([]);
    $paymentData = $paymentData->only([
        "status",
        "estimated_elec_billing_cost",
        "estimated_gas_billing_cost",
        "invited_at",
        "verified_at",
        "rejected_at",
        "customer_full_name",
        "customer_email",
        "customer_phone",
        "px_transaction_type",
        "px_amount",
        "px_currency_type",
        "px_txn_id",
        "px_is_enable_billing",
        "px_recurring_mode",
        "px_response_text",
        "px_card_type",
        "px_card_number",
        "px_card_expire_date",
        "px_card_holder_name",
        "px_dps_billing_id",
        "px_response_text_desc"
    ])->toArray();
    dd($paymentData);
});


Route::get('/{vue_capture?}', fn() => view('app'))
    ->where('vue_capture', '[\/\w\.-]*');

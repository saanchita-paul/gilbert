<?php

use App\Http\Controllers\Auth\AuthController;
use App\Services\Agency\ApplicationService;
use App\Services\FastConnectService;
use App\Services\Application\ServiceStatusFilterMapper;
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
    $timeSlot = \App\Models\OfficeAutoAssignTimeSlot::where('office_id', 1)
        ->where('day', strtolower(now()->format('l')))->first();
    dd($timeSlot->toArray());
    dd(strtolower(now()->format('l')));
    dd(\Carbon\Carbon::now()->timezone(\App\Services\TimeZoneService::getTimeZoneArea())->toDateTimeString());
    dd(\App\Services\TimeZoneService::getTimeZoneInt());
});

Route::get('/{vue_capture?}', fn() => view('app'))
    ->where('vue_capture', '[\/\w\.-]*');

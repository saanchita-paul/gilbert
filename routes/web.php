<?php

use App\Http\Controllers\Auth\AuthController;
use App\Models\ConnectionApplication;
use App\Jobs\AutoAssignAppToChatbotJob;
use App\Services\Agency\ApplicationService;
use App\Services\Agency\AutoAssignApplicationService;
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

Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::get('/powershop/payment/accept-invite/{id}', [PxPayController::class, 'acceptInvite']);

Route::get('/powershop/payment/success', [PxPayController::class, 'handleSuccess']);
Route::get('/powershop/payment/failed', [PxPayController::class, 'handleFailure']);

Route::get('/powershop/payment/callback', [PxPayController::class, 'handleCallback']);

Route::get('/email', function () {
    return response('hello world');
});

Route::get('mi-test', function () {
    $currentTime = Carbon::now()->timezone(TimeZoneService::getTimeZoneArea());
    $timeSlot = \App\Models\OfficeAutoAssignTimeSlot::first();
    $start_time = $timeSlot->start_time;
    $end_time = $timeSlot->end_time;

//dd($end_time < $start_time && $currentTime->gt($start_time) && $currentTime->lt($end_time));
    if ($start_time > $end_time && $currentTime->gt($start_time) && $currentTime->lt($end_time)) {
        dd('Current date');
    } elseif($start_time > $end_time && ($currentTime->gt($start_time) || $currentTime->lt($end_time))) {
        dd('Next date');
    } else {
        dd('Not in time slot');
    }

    $isAllowable = \Carbon\Carbon::parse('2022-12-23 21:00:00')->timezone(TimeZoneService::getTimeZoneArea())->isBetween($start_time, $end_time);
    dd($isAllowable);
});

Route::get('/{vue_capture?}', fn() => view('app'))
    ->where('vue_capture', '[\/\w\.-]*');

<?php

use App\Http\Controllers\Auth\AuthController;
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

Route::get('/agent-report', function () {
    return view('pdf/agent_report');
});

Route::get('/{vue_capture?}', fn() => view('app'))
    ->where('vue_capture', '[\/\w\.-]*');




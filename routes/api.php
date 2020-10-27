<?php

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


/**
 * test routes
 */
Route::get('lnn/bot_token', function () {
    return (new Encrypter(config('bot.encryption_key')))->decrypt(\request()->get('bot_token'), true);
});

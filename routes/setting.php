<?php

use Illuminate\Support\Facades\Route;

Route::get('is-chatbot-office', 'SettingsController@getIsChatbotOffice');
Route::post('is-chatbot-office', 'SettingsController@setIsChatbotOffice');

Route::get('test', function () {
    $user = \App\Models\User::with(['profile', 'profile.office', 'profile.agency'])->where('email', 'test4@demo.com')->first();
    dd($user);
});

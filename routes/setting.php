<?php

use Illuminate\Support\Facades\Route;

Route::get('is-chatbot-office', 'SettingsController@getIsChatbotOffice');
Route::post('is-chatbot-office', 'SettingsController@setIsChatbotOffice');

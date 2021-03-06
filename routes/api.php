<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/telegram/getUpdates','TelegramController@getUpdates');
Route::get('/telegram/sendMessage','TelegramController@sendMessage');
Route::post('/telegram/test','TelegramController@test');
Route::post('/'.env('TELEGRAM_BOT_TOKEN', 'YOUR BOT TOKEN HERE').'/webhook','TelegramController@webhook');
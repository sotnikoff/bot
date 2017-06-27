<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{

    public function getHome()
    {
        return view('home');
    }

    public function getUpdates()
    {
        $updates = Telegram::getUpdates();
        return $updates;
    }

    public function sendMessage()
    {
        Telegram::sendMessage([
            'chat_id' => '267788898',
            'text' => 'Привет, как дела?',
        ]);
    }

    public function test()
    {
        $response = Telegram::setWebhook(['url' => 'https://etg.hofch.ru/api/'.env('TELEGRAM_BOT_TOKEN','').'/webhook']);
        return $response;
    }

    public function webhook(Request $request)
    {
        Log::info($request);
    }

}
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

        $input = $request->all();
        $text = $input['message']['text'];
        if($text === 'Пошел на хуй' or $text === 'Пошёл на хуй'){
            $message = 'Сам пошел!';
        }else{
            $message = 'Я не знаю, что ты хочешь от меня, но я рад, что ты со мной заговорил!';
        }

        Telegram::sendChatAction([
            'chat_id' => $input['message']['from']['id'],
            'action' => 'typing',
        ]);

        sleep(3);

        Telegram::sendMessage([
            'chat_id' => $input['message']['from']['id'],
            'text' => $message,
        ]);

    }

}
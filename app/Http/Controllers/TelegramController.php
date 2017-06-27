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
        $text = mb_strtolower($input['message']['text']);
        /*
        if($text === 'пошел на хуй' or $text === 'пошёл на хуй'){
            $message = 'Сам пошел!';
        }else{
            $message = 'Я не знаю, что ты хочешь от меня, но я рад, что ты со мной заговорил!';
        }*/

        switch ($text)
        {
            case 'пошел на хуй':
                $message = 'Сам пошёл!';
                break;
            case 'пошёл на хуй':
                $message = 'Сам пошел!';
                break;
            case 'иди на хуй':
                $message = 'Сам иди!';
                break;
            case 'привет':
                $message = 'Доброго времени, '.$input['message']['from']['first_name'];
                break;
            case 'добрый день':
                $message = 'Доброго дня, '.$input['message']['from']['first_name'];
                break;
            case 'как дела?':
                $message = 'Отлично. Но как у тебя дела, мне не интересно, можешь не рассказывать';
                break;
            case 'что нового?':
                $message = 'Я новый!';
                break;
            case 'ты кто?':
                $message = 'Я - твое воображение';
                break;
            default:
                $message = 'Я не знаю, что ты хочешь от меня, но я рад, что ты со мной заговорил!';
        }

        sleep(rand(3,7));

        Telegram::sendChatAction([
            'chat_id' => $input['message']['from']['id'],
            'action' => 'typing',
        ]);

        $ratio = (int)floor(mb_strlen($message)/2.5);
        $sleep = rand(1,4)+$ratio;
        if($sleep>5){
            sleep(5);
        }else{
            sleep($sleep);
        }

        Telegram::sendMessage([
            'chat_id' => $input['message']['from']['id'],
            'text' => $message,
        ]);

    }

}
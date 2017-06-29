<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendTelegramMessage;

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

        if(isset($input['message']) and !empty($input['message']))
        {
            $message = $input['message'];
        }
        elseif (isset($input['edited_message']) and !empty($input['edited_message']))
        {
            $message = $input['edited_message'];
        }
        else
        {
            Log::info('no message sent');
            return ['no message'];
        }


        $text = trim(mb_strtolower($message['text']));
        $from = $message['from']['first_name'];

        $keyboard = [
            [
                [
                    'text'                  =>      'Отправить мое местоположение',
                    'request_contact'       =>      false,
                    'request_location'      =>      true,
                ],
            ],
        ];

        $reply_markup = Telegram::replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);



        dispatch(new SendTelegramMessage($input['message']['from']['id'],'Где Вы?', $reply_markup));

    }

}
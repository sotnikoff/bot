<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use App\TelegramLogic\TelegramSuggest;
use App\Jobs\SendTelegramMessage;
use App\TelegramState;

class TelegramController extends Controller
{

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

        if(isset($message['text']) and !empty($message['text']))
        {
            $text = $message['text'];
            $location = null;
        }
        elseif (isset($message['location']) and !empty($message['location']))
        {
            $location = $message['location'];
            $text = null;
        }
        else
        {
            Log::info('no text');
            return ['no text'];
        }




        //$from = $message['from']['first_name'];

        $state = TelegramState::firstOrCreate(
            [
                'telegram_user_id'  =>  (int)$message['from']['id'],
            ],
            [
                'telegram_message_id'   =>  $message['message_id'],
                'state'                 =>  json_encode(['state'=>null]),
                'workflow_name'         =>  'suggest'
            ]
        );


        if($text and $state->workflow_name === 'suggest')
        {

            $params = [
                'fromId'=>$message['from']['id'],
                'fromFirstName'=>$message['from']['first_name'],
                'text'=>$text,
                'messageId'=>$message['message_id'],
                'currentState'=>$state,
            ];

            $re = '/куд.{1,}(ход|пойти).{1,}/';
            $text = trim(mb_strtolower($text));
            preg_match_all($re, $text, $matches, PREG_SET_ORDER, 0);

            Log::info($matches);

            if(sizeof($matches) > 0)
            {
                $suggest = new TelegramSuggest($params);
                dispatch(new SendTelegramMessage($suggest->route(),'place:waitLocation',$state));

            }

        }

        if($state->workflow_name === 'place:waitLocation' and $location)
        {
            $params = [
                'fromId'=>$message['from']['id'],
                'fromFirstName'=>$message['from']['first_name'],
                'text'=>$location,
                'messageId'=>$message['message_id'],
                'currentState'=>$state,
            ];
        }

    }

}
<?php

namespace App\TelegramLogic;

use App\TelegramState;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;

class TelegramSuggest
{

    var $currentState;
    var $fromId;
    var $text;
    var $messageId;

    public function __construct($params)
    {

        $this->fromId = $params['fromId'];
        $this->text = $params['text'];
        $this->messageId = $params['messageId'];

        $this->currentState = $params['currentState'];

    }

    public function route()
    {

        $result = null;

        switch ($this->currentState->workflow_name)
        {
            case 'suggest':
                $result = $this->getLocationMessage();
                break;
            case 'place:waitLocation':
                $result = $this->getAnswerToLocation();
                break;
            default:
                $result = null;
        }

        return $result;

    }

    protected function getLocationMessage()
    {
        $keyboard = [
            [
                [
                    'text'                  =>      'Отдать местоположение',
                    'request_location'      =>      true,
                ],
                [
                    'text'                  =>      'Не хочу',
                    'request_location'      =>      false,
                ],
            ]
        ];

        $reply_markup = Telegram::replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $params = [
            'chat_id' => $this->fromId,
            'text' => 'Сообщите Ваше местоположение',
            'reply_markup' => $reply_markup,
        ];

        return $params;

    }

    protected function getAnswerToLocation()
    {
        $params = [
            'chat_id' => $this->fromId,
            'text' => $this->text['latitude'].' '.$this->text['longitude'],
        ];

        return $params;
    }

}
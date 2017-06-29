<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    var $message;
    var $to;
    var $reply_markup;


    public function __construct($to,$message,$reply_markup = null)
    {
        $this->message = $message;
        $this->to = $to;
        $this->reply_markup = $reply_markup;

    }

    public function handle()
    {

        $params = [
            'chat_id' => $this->to,
            'text' => $this->reply_markup,
        ];

        if(!$this->reply_markup)
        {
            $params['reply_markup'] = $this->reply_markup;
        }

        Telegram::sendMessage($params);


    }
}

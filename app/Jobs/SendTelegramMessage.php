<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\TelegramState;

class SendTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    var $params;
    var $workflow;
    var $telegramState;

    public function __construct($params,$workflow,TelegramState $state)
    {
        $this->params = $params;
        $this->workflow = $workflow;
        $this->telegramState = $state;
    }

    public function handle()
    {

        //Log::info($this->params);
        //Log::info(var_export($this->params,true));

        $res = Telegram::sendMessage($this->params);

        $messageId = $res->getMessageId();

        Log::info($messageId);

        $this->telegramState->workflow_name = $this->workflow;
        $this->telegramState->save();


    }
}

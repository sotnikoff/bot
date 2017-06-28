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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to,$message)
    {
        $this->message = $message;
        $this->to = $to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(rand(1,3));

        Telegram::sendChatAction([
            'chat_id' => $this->to,
            'action' => 'typing',
        ]);

        sleep(rand(1,3));

        Telegram::sendMessage([
            'chat_id' => $this->to,
            'text' => $this->message,
        ]);


    }
}

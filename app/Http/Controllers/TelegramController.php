<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Laravel\Facades\Telegram;

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
            'text' => 'asddddasd',
        ]);
    }

    public function test()
    {
        return 'test';
    }

}
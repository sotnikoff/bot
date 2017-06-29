<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramState extends Model
{
    protected $table = 'states';

    protected $fillable = ['telegram_user_id','telegram_message_id','state','workflow_name'];


}

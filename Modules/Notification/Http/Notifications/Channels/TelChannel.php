<?php

namespace Modules\Notification\Http\Notifications\Channels;

use Modules\Notification\Http\Notifications\Channels\Notice\TEL;
use Modules\Notification\Http\Notifications\MainNotification;

class TelChannel
{
    protected function send($user, MainNotification $notification): bool
    {
        # get information
        $message = $notification->toTel();
        //send message
        $chat_id = null;
        return $chat_id ? TEL::fire(message: $message, chat_id: $chat_id) : false;
    }

}

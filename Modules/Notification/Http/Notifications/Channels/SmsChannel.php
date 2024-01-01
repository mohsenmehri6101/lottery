<?php

namespace Modules\Notification\Http\Notifications\Channels;

use Modules\Notification\Http\Notifications\Channels\Notice\SMS;
use Modules\Notification\Http\Notifications\MainNotification;

class SmsChannel
{
    public function send($user, MainNotification $notification)
    {
        # get information
        $message = $notification->toSms();
        //send message
        $numberPhone = $user->phone ?? null;
        return $numberPhone ? SMS::fire($numberPhone, $message) : null;
    }

}

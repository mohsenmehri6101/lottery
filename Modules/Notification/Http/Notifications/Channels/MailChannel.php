<?php

namespace Modules\Notification\Http\Notifications\Channels;


use Modules\Notification\Http\Notifications\Channels\Notice\MAIL;
use Modules\Notification\Http\Notifications\MainNotification;

class MailChannel
{
    public function send($user,MainNotification $notification)
    {
        # get information
        $message=$notification->toEmail();

        //send message
        $email= $user->email ?? null;
        return $email ? MAIL::fire(message: $message, email: $email) : null;
    }

}

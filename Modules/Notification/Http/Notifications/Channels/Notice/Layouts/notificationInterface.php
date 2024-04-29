<?php

namespace Modules\Notification\Http\Notifications\Channels\Notice\Layouts;

interface notificationInterface
{
    public static function saveInDB(string $message, string $postLink);

    public static function fire(string $message, $postLink);

}

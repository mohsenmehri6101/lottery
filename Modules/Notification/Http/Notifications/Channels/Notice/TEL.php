<?php

namespace Modules\Notification\Http\Notifications\Channels\Notice;

use Modules\Notification\Http\Notifications\Channels\Notice\Layouts\NoticeDB;
use Modules\Notification\Http\Notifications\Channels\Notice\Layouts\notificationInterface;

class TEL extends NoticeDB implements notificationInterface
{
    public const TYPE = "TEL";

    public static function saveInDB(string $message, string $chat_id)
    {
//        $to=Contact::GiveMeUserIDForThisTelegramId($chat_id);
//        static::noticeInDatabase($message,$to,self::TYPE);
    }

    public static function fire(string $message, $chat_id)
    {
        return true;
//        #save in DB
//        static::saveInDB($chat_id,$message);
//        # send email
//        return false;
        #return static::sendMessage($idChat,$textMessage) ? true : false;
    }

    private static function sendMessage($text, $chat_id)
    {
//        $telegram=new Telegram(env('TELEGRAM_TOKEN',false));
//        $content = array('chat_id' => $chat_id, 'text' =>$text);
//        static::$telegram->sendMessage($content);
    }
}

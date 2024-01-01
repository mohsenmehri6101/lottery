<?php

namespace Modules\Notification\Http\Notifications\Channels\Notice;

use Modules\Notification\Http\Notifications\Channels\Notice\Layouts\NoticeDB;
use Modules\Notification\Http\Notifications\Channels\Notice\Layouts\notificationInterface;

class SMS  extends NoticeDB implements notificationInterface
{
    public  const TYPE="SMS";

    public static function saveInDB($message,$postLink){

//        $user=User::wherePhone($postLink)->first();
//        $to=isset($user) ? $user->id : null;

//        return static::noticeInDatabase($message,$to,self::TYPE);
    }

    private static function createMessage($message): string
    {
//        return (string)config('app.name')."   ".$message;
    }


    /**
     * @param $message
     * @param $phone
     * @return bool
     */
    public static function fire($message, $phone): bool
    {
//        # save DB
//        $notice = static::saveInDB($phone, $message);
//
//        # send Message
//        return RayganSms::sendMessage($phone,static::createMessage($message)) ? true : false;
        return true;
    }
}

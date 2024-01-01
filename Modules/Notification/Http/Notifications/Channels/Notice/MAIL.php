<?php

namespace Modules\Notification\Http\Notifications\Channels\Notice;

use Modules\Notification\Http\Notifications\Channels\Notice\Layouts\NoticeDB;
use Modules\Notification\Http\Notifications\Channels\Notice\Layouts\notificationInterface;

class MAIL extends NoticeDB implements notificationInterface
{
    public const TYPE = "MAIL";

    public static function saveInDB(string $message, string $email)
    {

        # to
//        $user=User::whereEmail($email)->first();
//        $to=isset($user) ? $user->id : null;
//
//        static::noticeInDatabase($message,$to,self::TYPE);
    }


    /**
     * @param $message
     * @param $email
     * @return bool
     */
    public static function fire($message, $email): bool
    {
//        #save in DB
//        static::saveInDB($email,$message);
//        # send email
//        return MailFacadeLaravel::to($email)->send(new MainMail($message)) ? true : false;
    }
}

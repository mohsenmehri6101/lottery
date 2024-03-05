<?php

namespace Modules\Notification\Services\Sms;


interface SmsInterface
{
    public static function send_sms(string|int $mobile, string $message = null, bool $throwException = true): bool;
}

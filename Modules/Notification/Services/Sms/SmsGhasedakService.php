<?php

namespace Modules\Notification\Services\Sms;

use Ghasedak\Exceptions\ApiException;
use Ghasedak\Exceptions\HttpException;
use Exception;
use Ghasedak\GhasedakApi;

class SmsGhasedakService implements SmsInterface
{
    const DEFAULT_LINE_NUMBER = '10008566';

    private static function getApiKeyGhasedak()
    {
        return config('configs.notifications.sms.ghasedak.api_key');
    }

    public static function send_sms(string|int $mobile, string $message = null, bool $throwException = true): bool
    {
        try {
            $api_key = self::getApiKeyGhasedak();
            $mobile = trim($mobile);
            $ghasedak_api = new GhasedakApi($api_key);
            $ghasedak_api->SendSimple($mobile, $message, self::DEFAULT_LINE_NUMBER);
            return true;
        } catch (ApiException|HttpException|Exception $e) {
            if ($throwException) {
                throw $e;
            } else {
                report($e);
                return false;
            }
        }
    }
}
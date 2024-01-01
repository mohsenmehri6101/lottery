<?php

namespace Modules\Notification\Services\Sms;

use Ghasedak\Exceptions\ApiException;
use Ghasedak\Exceptions\HttpException;
use Exception;
use Ghasedak\GhasedakApi;

class SmsGhasedakService implements SmsInterface
{
    /*
    10008566
    300002525
    5000121212
    5000270
    210002100
    */
    const DEFAULT_LINE_NUMBER = '10008566';

    private static function getApiKeyGhasedak()
    {
        return config('configs.notifications.sms.ghasedak.api_key');
    }

    public static function send_sms(string|int $mobile, string $message = null): bool
    {
        try {
            $api_key = self::getApiKeyGhasedak();
            $mobile = trim($mobile);
            $ghasedak_api = new GhasedakApi($api_key);
            $ghasedak_api->SendSimple($mobile, $message, self::DEFAULT_LINE_NUMBER);
            return true;
        } catch (ApiException|HttpException|Exception $e) {
            report($e);
            return false;
        }
    }
}

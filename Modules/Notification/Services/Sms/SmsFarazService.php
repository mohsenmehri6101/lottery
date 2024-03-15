<?php

namespace Modules\Notification\Services\Sms;

use Exception;
use Illuminate\Support\Facades\Http;
use IPPanel\Client as IPPanelClient;

class SmsFarazService implements SmsInterface
{

    private static string $baseUrl;
    private static string $apiKey;

    /*  ######################### */

    public static function initialize(): void
    {
        self::$baseUrl = config('configs.notifications.sms.farazsms.base_url', 'https://ippanel.com/services.jspd');
        self::$apiKey = config('configs.notifications.sms.farazsms.api_key');
    }

    public static function send_sms(string|int $mobile, string $message = null, bool $throwException = true): bool
    {
        try {
            $apiKey = config('configs.notifications.sms.farazsms.api_key');
            $client = new IPPanelClient($apiKey);

            $originator = config('configs.notifications.sms.farazsms.sender_number');
            $recipient = (string) $mobile;
            $summary = 'Sending SMS via IPPanel SDK';

            $client->send($originator, [$recipient], $message, $summary);

            return true;
        } catch (Exception $exception) {
            if ($throwException) {
                throw $exception;
            } else {
                report($exception);
                return false;
            }
        }
    }

}

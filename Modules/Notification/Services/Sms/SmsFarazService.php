<?php

namespace Modules\Notification\Services\Sms;

use Exception;
use Illuminate\Support\Facades\Http;

class SmsFarazService implements SmsInterface
{
    private static string $baseUrl;
    private static string $apiKey;

    public static function initialize(): void
    {
        self::$baseUrl = config('configs.sms.farazsms.base_url', 'https://ippanel.com/services.jspd');
        self::$apiKey = config('configs.sms.farazsms.api_key');
    }

    public static function send_sms(string|int $mobile, string $message = null, bool $throwException = true): bool
    {
        self::initialize();
        try {
            $response = Http::post(self::$baseUrl,[
                'op' => 'send',
                'uname' => 'YOUR_USERNAME',
                'pass' => 'YOUR_PASSWORD',
                'from' => 'YOUR_SENDER_NUMBER',
                'message' => $message,
                'to' => $mobile,
                'res' => 'json'
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['ret']) && $responseData['ret'] == 0) {
                return true;
            } else {
                $errorMessage = isset($responseData['message']) ? $responseData['message'] : 'Unknown error';
                if ($throwException) {
                    throw new Exception("Error sending SMS: " . $errorMessage);
                } else {
                    report(new Exception("Error sending SMS: " . $errorMessage));
                    return false;
                }
            }
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

<?php

namespace Modules\Notification\Services\Sms;

use Modules\Exception\Services\Contracts\SmsException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class SmsMedianaService implements SmsInterface
{
    private static $baseUrl;
    private static $apiKey;

    public static function initialize()
    {
        self::$baseUrl = Config::get('services.median.base_url', 'DEFAULT_MEDIAN_BASE_URL');
        self::$apiKey = Config::get('services.median.api_key', 'DEFAULT_MEDIAN_API_KEY');
    }

    public static function send_sms(string|int $mobile, string $message = null): bool
    {
        self::initialize();

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::$apiKey,
            ])->post(self::$baseUrl . '/send-sms', [
                'recipient' => [$mobile],
                'sender' => 'YOUR_SENDER_NUMBER',
                'time' => now()->toIso8601String(),
                'message' => $message,
            ]);

            $responseData = $response->json();

            if ($response->ok() && $responseData['status'] === 'OK') {
                return true;
            } else {
                // Handle error by throwing an SmsException
                throw new SmsException("Error sending SMS: " . $responseData['errorMessage'], $response->status());
            }
        } catch (\Throwable $e) {
            // Log or handle the exception
            report($e);
            return false;
        }
    }
    
}
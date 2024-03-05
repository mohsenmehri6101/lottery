<?php

namespace Modules\Notification\Services\Sms;

use Exception;
use Illuminate\Support\Facades\Http;

class SmsMedianaService implements SmsInterface
{
    private static $baseUrl = 'MEDIAN_API_BASE_URL';
    private static $apiKey = 'MEDIAN_API_KEY';

    public static function send_sms(string|int $mobile, string $message = null): bool
    {
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
                // Handle error if necessary
                return false;
            }
        } catch (Exception $e) {
            // Log or handle the exception
            report($e);
            return false;
        }
    }
}
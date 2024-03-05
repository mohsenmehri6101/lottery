<?php

namespace Modules\Notification\Services\Sms;

use GuzzleHttp\Client;
use Exception;

class SmsMedianaService implements SmsInterface
{
    private static $baseUrl = 'MEDIAN_API_BASE_URL';
    private static $apiKey = 'MEDIAN_API_KEY';

    public static function send_sms(string|int $mobile, string $message = null): bool
    {
        try {
            $client = new Client([
                'base_uri' => self::$baseUrl,
                'timeout' => 10,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . self::$apiKey,
                ],
            ]);

            $response = $client->post('/send-sms', [
                'json' => [
                    'recipient' => [$mobile],
                    'sender' => 'YOUR_SENDER_NUMBER',
                    'time' => date('c'),
                    'message' => $message,
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            if ($responseData['status'] === 'OK') {
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

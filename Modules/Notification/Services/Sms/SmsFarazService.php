<?php

namespace Modules\Notification\Services\Sms;

use Exception;
use Illuminate\Support\Facades\Http;
use IPPanel\Client as IPPanelClient;

class SmsFarazService implements SmsInterface
{
    public static function createPattern(IPPanelClient $client, string $message): string
    {
        # Define pattern variables
        $pattern_variables = [
            'message' => 'string',
        ];

        // Create the pattern
        $pattern_code = $client->createPattern("%message% \n سلام سالن", "description",
            $pattern_variables, '%', false);

        return $pattern_code;
    }

    public static function send_sms(string|int $mobile, string $message = null, bool $throwException = true): bool
    {
        try {
            $apiKey = config('configs.notifications.sms.farazsms.api_key');
            $patternId = 'urwm74gu1e'; // Replace with your pattern ID
            $senderNumber = '3000505'; // Replace with your sender number
            $value1 = urlencode($message); // Encode the message
            $baseUrl = '';
            $url = $baseUrl . '/?apikey=' . $apiKey . '&pid=' . $patternId . '&fnum=' . $senderNumber . '&tnum=' . $mobile . '&p1=message' . '&v1=' . $value1;
            $response = Http::get($url);
            $responseData = $response->json();
            if ($response->successful() && $responseData['status'] === 'OK') {
                return true;
            } else {
                // Log or handle the error
                if ($throwException) {
                    throw new Exception("Error sending SMS: " . $responseData['errorMessage']);
                } else {
                    report(new Exception("Error sending SMS: " . $responseData['errorMessage']));
                    return false;
                }
            }
        } catch (Exception $exception) {
            # Log or handle the exception
            if ($throwException) {
                throw $exception;
            } else {
                report($exception);
                return false;
            }
        }
    }


}

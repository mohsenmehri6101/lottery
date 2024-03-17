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
        # self::initialize();
        try {
            $api_key = config('configs.notifications.sms.farazsms.api_key');
            $originator = config('configs.notifications.sms.farazsms.sender_number');

            # Create the pattern and get the pattern code.
            $client = new IPPanelClient($api_key);
            $pattern_code = self::createPattern($client, $message);
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'apikey' => $api_key,
            ])->post("https://api2.ippanel.com/api/v1/sms/pattern/normal/send",[
                'code' => $pattern_code,
                'sender' => $originator,
                'recipient' => $mobile,
                'variable' => [
                    'message' => $message,
                ],
            ]);

            $responseData = $response->json();

            if ($response->ok() && $responseData['status'] === 'OK') {
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
            // Log or handle the exception
            if ($throwException) {
                throw $exception;
            } else {
                report($exception);
                return false;
            }
        }
    }

    //    public static function send_sms(string|int $mobile, string $message = null, bool $throwException = true): bool
    //    {
    //        try {
    //            # Initialize the IPPanel client with your API key
    //            $api_key = config('configs.notifications.sms.farazsms.api_key');
    //            $client = new IPPanelClient($api_key);
    //
    //            # Create the pattern and get the pattern code
    //            $pattern_code = self::createPattern($client, $message);
    //            $originator = config('configs.notifications.sms.farazsms.sender_number');
    //
    //            # Send the message using the predefined pattern
    //            $messageId = $client->sendPattern(
    //                $pattern_code,                                       // pattern code
    //                $originator,                                             // originator
    //                $mobile,                                            // recipient
    //                ['message' => $message]                             // pattern values
    //            );
    //
    //            return true;
    //        } catch (Exception $exception) {
    //            if ($throwException) {
    //                throw $exception;
    //            } else {
    //                report($exception);
    //                return false;
    //            }
    //        }
    //    }

}

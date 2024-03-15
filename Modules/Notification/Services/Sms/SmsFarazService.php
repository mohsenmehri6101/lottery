<?php

namespace Modules\Notification\Services\Sms;

use Exception;
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
            // Initialize the IPPanel client with your API key
            $api_key = config('configs.notifications.sms.farazsms.api_key');
            $client = new IPPanelClient($api_key);

            // Create the pattern and get the pattern code
            $pattern_code = self::createPattern($client, $message);
            $originator = config('configs.notifications.sms.farazsms.sender_number');

            // Send the message using the predefined pattern
            $messageId = $client->sendPattern(
                $pattern_code,                                       // pattern code
                $originator,    // originator
                $mobile,                                            // recipient
                ['message' => $message]                             // pattern values
            );

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

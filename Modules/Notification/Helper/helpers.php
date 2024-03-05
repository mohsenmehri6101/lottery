<?php

if (!function_exists('send_sms')) {
    function send_sms(string|int $mobile, string $message = null, $service = 'ghasedak'): bool
    {
        try {
            $app_env = env('APP_ENV', 'local');
            //todo uncomment this section for production
            if ($app_env == 'production') {
                if ($service === 'ghasedak') {
                    return \Modules\Notification\Services\Sms\SmsGhasedakService::send_sms(mobile: $mobile, message: $message);
                } else if ($service === 'mediana') {
                    return \Modules\Notification\Services\Sms\SmsMedianaService::send_sms(mobile: $mobile, message: $message);
                }
            } else {
                \Illuminate\Support\Facades\Log::info('send_sms', [$mobile, $message]);
            }
            return true;
        } catch (\Exception $exception) {
            report($exception);
            return false;
        }
    }
}

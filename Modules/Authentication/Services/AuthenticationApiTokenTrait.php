<?php

namespace Modules\Authentication\Services;

use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

trait AuthenticationApiTokenTrait
{
    public static function setApiKey($mobile = null): bool
    {
        $length_api_key_string = config('configs.api_key.length');
        $expired_time = now()->addMinutes(config('configs.api_key.expired_time_minutes'));
        $api_key = random_string(length: $length_api_key_string);
        return cache()->set($api_key, ['mobile' => $mobile, 'expired_time' => $expired_time]);
    }

    public static function deleteApiKey($apiKey = null): bool
    {
        $apiKey = $apiKey ?? request()->header('api-key');
        return cache()->forget($apiKey);
    }

    public static function checkApiKey($api_key, $mobile = null): bool
    {
        $information_in_cache = cache()->get($api_key);
        $mobile_in_cache = $information_in_cache['mobile'] ?? null;
        $expired_time = $information_in_cache['expired_time'] ?? null;
        $expired_time = Carbon::make($expired_time);
        if ($expired_time < now()) {
            $message = trans('custom.users.messages.expired_time_api_key');
            throw new Exception(message: $message, code: HttpFoundationResponse::HTTP_BAD_REQUEST/* 400 */);
        }
        return $mobile_in_cache/*$mobile_in_cache == $mobile*/ ;
    }
}

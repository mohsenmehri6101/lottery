<?php

if (!function_exists('config_')) {
    function config_($key = null, $default = null, $title = null)
    {
        $config_default_value = config($key, $default);
        $config_in_db = \Modules\Config\Services\ConfigService::firstOrCreate(key: $key, value: $config_default_value, title: $title);
        return $config_in_db?->value ?? $config_default_value ?? $default;
    }
}

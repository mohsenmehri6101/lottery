<?php

namespace App\Permissions;

trait EnumToArray
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function get_key_values(): array
    {
        return array_combine(self::values(), self::names());
    }

    public static function get_values_keys(): array
    {
        return array_combine(self::names(),self::values());
    }
}

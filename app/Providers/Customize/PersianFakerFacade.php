<?php

namespace App\Providers\Customize;

class PersianFakerFacade
{
    protected static function getFacadeAccessor(): string
    {
        return 'persian-faker';
    }
}

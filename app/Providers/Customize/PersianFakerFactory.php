<?php

namespace App\Providers\Customize;

use Faker\Factory as BaseFactory;

class PersianFakerFactory extends BaseFactory
{
    public static function create($locale = self::DEFAULT_LOCALE)
    {
        $faker = parent::create();

        // Add your custom provider to the Faker instance
        $faker->addProvider(new PersianDataProvider($faker));

        return $faker;
    }
}

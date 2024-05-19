<?php

namespace Modules\Gym\Database\factories;

use App\Providers\Customize\PersianDataProvider;
use App\Providers\Customize\PersianFakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Gym\Entities\Sport;

class SportFactory extends Factory
{
    protected $model = Sport::class;

    public function definition(): array
    {
        /** @var PersianDataProvider $persianFaker */
        $persianFaker = PersianFakerFactory::create();

        return [
            'name' => $persianFaker->persianSportUnique(),
        ];
    }

}

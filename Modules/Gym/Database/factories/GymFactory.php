<?php

namespace Modules\Gym\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Gym\Entities\Gym;
use App\Providers\Customize\PersianDataProvider;
use App\Providers\Customize\PersianFakerFactory;

class GymFactory extends Factory
{
    protected $model = Gym::class;

    public function definition(): array
    {
        /** @var PersianDataProvider $persianFaker */
        $persianFaker = PersianFakerFactory::create();

        return [
            'name' => $persianFaker->persianGymUnique(),
            'description' => $persianFaker->persianDescription(),
            'latitude' => $persianFaker->persianLatitude(),
            'longitude' => $persianFaker->persianLongitude(),
            'city_id' => $this->faker->numberBetween(1, 3),
            'address' => $persianFaker->persianAddress(),
            'score' => $this->faker->numberBetween(1, 5),
            'status' => $this->faker->numberBetween(0, 2),
        ];
    }

}

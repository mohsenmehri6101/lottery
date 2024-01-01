<?php

namespace Modules\Gym\Database\factories;

use App\Providers\Customize\PersianDataProvider;
use App\Providers\Customize\PersianFakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Gym\Entities\Tag;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        /** @var PersianDataProvider $persianFaker */
        $persianFaker = PersianFakerFactory::create();

        return [
            'tag' => $persianFaker->persianTagUnique(),
        ];
    }
}

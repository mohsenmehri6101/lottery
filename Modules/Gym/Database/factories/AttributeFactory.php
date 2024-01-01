<?php

namespace Modules\Gym\Database\factories;

use App\Providers\Customize\PersianDataProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Gym\Entities\Attribute;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition(): array
    {
        /** @var PersianDataProvider $persianFaker */
        $persianFaker = app(PersianDataProvider::class);

        return [
            'name' => $persianFaker->persianAttributeUnique(),
        ];
    }

}

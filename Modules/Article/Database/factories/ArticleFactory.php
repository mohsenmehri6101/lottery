<?php

namespace Modules\Article\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Article\Entities\Article;
use App\Providers\Customize\PersianDataProvider;
use App\Providers\Customize\PersianFakerFactory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        /** @var PersianDataProvider $persianFaker */
        $persianFaker = PersianFakerFactory::create();

        return [
            'name' => $persianFaker->persianArticleUnique(),
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

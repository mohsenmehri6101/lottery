<?php

namespace Modules\Article\Database\factories;

use App\Providers\Customize\PersianDataProvider;
use App\Providers\Customize\PersianFakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Article\Entities\Keyword;

class KeywordFactory extends Factory
{
    protected $model = Keyword::class;

    public function definition(): array
    {
        /** @var PersianDataProvider $persianFaker */
        $persianFaker = PersianFakerFactory::create();

        return [
            'keyword' => $persianFaker->persianKeywordUnique(),
        ];
    }

}

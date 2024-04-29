<?php

namespace Modules\Slider\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use Modules\Slider\Entities\Slider;

class SliderRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Slider::class;
    }

    /**
     * @return string[]
     */
    public function relations(): array
    {
        return Slider::$relations_;
        // TODO: Implement relations() method.
    }
}

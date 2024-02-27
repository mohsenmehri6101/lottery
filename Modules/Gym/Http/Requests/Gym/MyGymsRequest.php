<?php

namespace Modules\Gym\Http\Requests\Gym;

class MyGymsRequest extends GymIndexRequest
{
    public function authorize(): bool
    {
        return is_gym_manager();
    }

}

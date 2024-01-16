<?php

namespace Modules\Gym\Http\Requests\Gym;

use Illuminate\Foundation\Http\FormRequest;

class GymLikeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gym_id' => 'required|filled|exists:gyms,id',
            'type' => 'required|filled|string|in:like,dislike',
        ];
    }

}

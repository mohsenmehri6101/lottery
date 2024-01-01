<?php

namespace Modules\Gym\Http\Requests\Sport;

use Illuminate\Foundation\Http\FormRequest;

class DeleteSportToGymRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gym_id' => 'required|exists:gyms,id',
            'touch' => 'nullable|boolean',
            'sport_id' => 'required_without:sports|filled|exists:sports,id',
            'sports' => 'required_without:sport_id|array',
            'sports.*' => 'required|filled|exists:sports,id',
        ];
    }
}

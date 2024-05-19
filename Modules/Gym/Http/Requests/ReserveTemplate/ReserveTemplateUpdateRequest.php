<?php

namespace Modules\Gym\Http\Requests\ReserveTemplate;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\ReserveTemplate;

class ReserveTemplateUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $statuses_gender_acceptance = implode(',', ReserveTemplate::getStatusGenderAcceptance());

        return [
            'from' => 'nullable',
            'to' => 'nullable',
            // todo not should be gym-manager this field update. set condition from here.
            'gym_id' => 'nullable|exists:gyms,id',
            'week_number' => 'nullable|numeric|min:1|max:7',
            'price' => 'nullable',
            'cod' => 'nullable|boolean',
            'is_ball' => 'nullable|boolean',
            'gender_acceptance' => "nullable|numeric|in:$statuses_gender_acceptance",
            'discount' => 'nullable|numeric|min:1|max:100',
            'status' => 'nullable|numeric',// todo should be complete set $statuses
        ];
    }

}

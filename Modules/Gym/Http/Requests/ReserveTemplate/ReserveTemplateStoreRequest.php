<?php

namespace Modules\Gym\Http\Requests\ReserveTemplate;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\ReserveTemplate;
use Modules\Gym\Rules\UniqueReserveTemplateStore;

class ReserveTemplateStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $statuses_gender_acceptance = implode(',', ReserveTemplate::getStatusGenderAcceptance());

        return [
            'from' => 'required',
            'to' => 'required',
            'gym_id' => 'required|exists:gyms,id',
            'week_number' => 'required|numeric|min:1|max:7',
            'price' => 'required',
            'cod' => 'nullable|boolean',
            'is_ball' => 'nullable|boolean',
            'gender_acceptance' => "nullable|numeric|in:$statuses_gender_acceptance",
            'discount' => 'nullable|numeric',
            'status' => 'nullable|numeric',// todo should be complete set $statuses
        ];
    }
}

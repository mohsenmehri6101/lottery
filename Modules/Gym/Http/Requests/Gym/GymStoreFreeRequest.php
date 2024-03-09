<?php

namespace Modules\Gym\Http\Requests\Gym;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\ReserveTemplate;

class GymStoreFreeRequest extends FormRequest
{
    public function rules(): array
    {
        $status_gender_acceptances = implode(',', ReserveTemplate::getStatusGenderAcceptance());

        return [
            'mobile' => 'required|filled',
            'name' => 'required|filled',
            'description' => 'nullable',
            'price' => 'nullable',
            'gender_acceptance' => "nullable|numeric|in:$status_gender_acceptances",
            'priority_show' => "nullable|numeric",
            'city_id' => 'required|numeric|exists:cities,id',
            'short_address' => 'nullable',
            'address' => 'nullable',
        ];

    }
    public function attributes(): array
    {
        return [
            'name' => __('custom.gyms.gyms.name'),
            'description' => __('custom.gyms.gyms.description'),
            'price' => __('custom.gyms.gyms.price'),
            'gender_acceptance' => __('custom.gyms.gyms.gender_acceptance'),
            'city_id' => __('custom.gyms.gyms.city_id'),
            'short_address' => __('custom.gyms.gyms.short_address'),
            'address' => __('custom.gyms.gyms.address'),
        ];
    }
}

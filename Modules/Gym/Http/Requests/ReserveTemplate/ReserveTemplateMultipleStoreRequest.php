<?php

namespace Modules\Gym\Http\Requests\ReserveTemplate;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\ReserveTemplate;

class ReserveTemplateMultipleStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $statuses_gender_acceptance = implode(',', ReserveTemplate::getStatusGenderAcceptance());
        $statuses_reserve_template = implode(',', ReserveTemplate::getStatus());

        return [
            'reserve_templates.*' => 'nullable|array',
            'reserve_templates.*.from' => 'required',
            'reserve_templates.*.to' => 'required',
            'reserve_templates.*.gym_id' => 'required|exists:gyms,id',
            'reserve_templates.*.week_number' => 'required|numeric|min:1|max:7',
            'reserve_templates.*.price' => 'nullable',
            'reserve_templates.*.cod' => 'nullable|boolean',
            'reserve_templates.*.is_ball' => 'nullable|boolean',
            'reserve_templates.*.gender_acceptance' => "nullable|numeric|in:$statuses_gender_acceptance",
            'reserve_templates.*.discount' => 'nullable|numeric',
            'reserve_templates.*.status' => "nullable|numeric|in:$statuses_reserve_template",
        ];
    }
}

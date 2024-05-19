<?php

namespace Modules\Gym\Http\Requests\ReserveTemplate;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\ReserveTemplate;
use function convert_withs_from_string_to_array;

class ReserveTemplateIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', ReserveTemplate::$relations_);
        $status_gender_acceptances = implode(',', ReserveTemplate::getStatusGenderAcceptance());

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',

            'order_by' => 'nullable',
            'direction_by' => 'nullable',

            'id' => 'nullable|exists:categories,id',
            'from' => 'nullable',
            'to' => 'nullable',
            'gym_id' => 'nullable|exists:gyms,id',
            'week_number' => 'nullable|numeric|min:1|max:7',
            'user_creator' => 'nullable|exists:users,id',
            'user_editor' => 'nullable|exists:users,id',
            'price' => 'nullable',
            'cod' => 'nullable|boolean',
            'is_ball' => 'nullable|boolean',
            'gender_acceptance' => "nullable|numeric|in:$status_gender_acceptances",
            'discount' => 'nullable|numeric|min:0|max:100',
            'status' => 'nullable|numeric',// todo should be complete set $statuses

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",

            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }
}

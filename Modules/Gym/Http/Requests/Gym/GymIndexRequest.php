<?php

namespace Modules\Gym\Http\Requests\Gym;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\ReserveTemplate;
use Modules\Gym\Entities\Gym;
use function convert_withs_from_string_to_array;

class GymIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Gym::$relations_);
        $list_status_allowable = trim(implode(',', Gym::getStatusGym()));
        $status_gender_acceptances = implode(',', ReserveTemplate::getStatusGenderAcceptance());

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:gyms,id',
            'search' => 'nullable|filled|min:3',
            'name' => 'nullable',
            'description' => 'nullable',
            'price' => 'nullable',
            'max_price' => 'nullable',
            'min_price' => 'nullable',
            'gender_acceptance' => "nullable|numeric|in:$status_gender_acceptances",
            'priority_show' => "nullable|numeric",
            'profit_share_percentage' => 'nullable|min:0|max:100',

            'city_id' => 'nullable|exists:cities,id',
            'short_address' => "nullable",
            'address' => "nullable",

            'score' => "nullable",
            'status' => "nullable|numeric|in:$list_status_allowable",
            'like_count' => 'nullable',
            'dislike_count' => 'nullable',
            'user_id' => 'nullable|exists:users,id',
            'user_creator' => 'nullable|exists:users,id',
            'user_editor' => 'nullable|exists:users,id',
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
            'dated_at' => 'nullable',
            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }

}

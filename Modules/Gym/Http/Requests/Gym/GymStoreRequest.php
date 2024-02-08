<?php

namespace Modules\Gym\Http\Requests\Gym;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\ReserveTemplate;
use Modules\Gym\Entities\Gym;

class GymStoreRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('is_ball')) {
            $is_ball = $this->get('is_ball');
            $this->merge(['is_ball' => $is_ball ? 1 : 0]);
        }
    }

    public function rules(): array
    {
        $list_status_allowable = trim(implode(',', Gym::getStatusGym()));
        $status_gender_acceptances = implode(',', ReserveTemplate::getStatusGenderAcceptance());

        return [
            'name' => 'required|filled',
            'description' => 'nullable',
            'price' => 'nullable',
            'status' => "nullable|numeric|in:$list_status_allowable",
            'gender_acceptance' => "nullable|numeric|in:$status_gender_acceptances",
            'user_id' => 'nullable|exists:users,id',
            'profit_share_percentage' => 'nullable|min:0|max:100',
            'is_ball' => 'nullable|in:0,1',
            'ball_price' => 'nullable',
            'city_id' => 'required|numeric|exists:cities,id',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'short_address' => 'nullable',
            'address' => 'nullable',

            # tags
            'tag_id' => 'nullable|filled|exists:tags,id',
            'tags' => 'nullable|array',
            'tags.*' => 'required|filled|exists:tags,id',

            # categories
            'category_id' => 'nullable|filled|exists:categories,id',
            'categories' => 'nullable|array',
            'categories.*' => 'required|filled|exists:categories,id',

            # keywords
            'keyword_id' => 'nullable|filled|exists:keywords,id',
            'keywords' => 'nullable|array',
            'keywords.*' => 'required|filled|exists:keywords,id',

            # sports
            'sport_id' => 'nullable|filled|exists:sports,id',
            'sports' => 'nullable|array',
            'sports.*' => 'required|filled|exists:sports,id',

            # attributes
            'attribute_id' => 'nullable|filled|exists:gyms_attributes,id',
            'attributes' => 'nullable|array',
            'attributes.*' => 'required|filled|exists:gyms_attributes,id',

            # images
            'images' => 'nullable|array',
            'images.*' => 'required',

            # reserve template data
            'time_template.*' => 'nullable|array',
            'time_template.from' => 'nullable|date_format:H:i',
            'time_template.to' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value !== null && $value !== '24:00') {
                        if (!\DateTime::createFromFormat('H:i', $value)) {
                            $fail('The ' . $attribute . ' does not match the format H:i.');
                        }
                    }
                },
            ],
            'time_template.break_time' => 'nullable|numeric|min:1',
            'time_template.price' => 'nullable|numeric|min:0',
            'time_template.gender_acceptance' => "nullable|numeric|in:$status_gender_acceptances",
            'time_template.week_numbers' => 'nullable|array',
            'time_template.week_numbers.*' => 'nullable|integer|in:1,2,3,4,5,6,7',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('custom.gyms.gyms.name'),
            // todo add others like this
            'description' => 'description',
            'price' => 'price',
            'status' => 'status',
            'gender_acceptance' => 'gender_acceptance',
            'user_id' => 'user_id',
            'profit_share_percentage' => 'profit_share_percentage',
            'is_ball' => 'is_ball',
            'ball_price' => 'ball_price',
            'city_id' => 'city_id',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'short_address' => 'short_address',
            'address' => 'address',
        ];
    }
}

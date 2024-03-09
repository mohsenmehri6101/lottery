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

        $rules = [
            'name' => 'required|filled',
            'description' => 'nullable',
            'reason_gym_disabled' => 'nullable',
            'price' => 'nullable',
            'status' => "nullable|numeric|in:$list_status_allowable",
            'gender_acceptance' => "nullable|numeric|in:$status_gender_acceptances",
            'priority_show' => "nullable|numeric",
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

        if (is_admin() || is_super_admin()) {
            $rules = [
                ...$rules,
                'user_gym_manager_id' => 'nullable|exists:users,id',
            ];
        }

        return $rules;
    }
    public function attributes(): array
    {
        return [
            'name' => __('custom.gyms.gyms.name'),
            'description' => __('custom.gyms.gyms.description'),
            'price' => __('custom.gyms.gyms.price'),
            'status' => __('custom.gyms.gyms.status'),
            'gender_acceptance' => __('custom.gyms.gyms.gender_acceptance'),
            'user_id' => __('custom.gyms.gyms.user_id'),
            'profit_share_percentage' => __('custom.gyms.gyms.profit_share_percentage'),
            'is_ball' => __('custom.gyms.gyms.is_ball'),
            'ball_price' => __('custom.gyms.gyms.ball_price'),
            'city_id' => __('custom.gyms.gyms.city_id'),
            'latitude' => __('custom.gyms.gyms.latitude'),
            'longitude' => __('custom.gyms.gyms.longitude'),
            'short_address' => __('custom.gyms.gyms.short_address'),
            'address' => __('custom.gyms.gyms.address'),

            # ################
            # tags
            'tag_id' => __('custom.gyms.tags.id'),
            'tags' => __('custom.gyms.tags'),
            'tags.*' => __('custom.gyms.tags'),

            # categories
            'category_id' => __('custom.gyms.categories.id'),
            'categories' => __('custom.gyms.categories'),
            'categories.*' => __('custom.gyms.categories'),

            # keywords
            'keyword_id' => __('custom.gyms.keywords.id'),
            'keywords' => __('custom.gyms.keywords'),
            'keywords.*' => __('custom.gyms.keywords'),

            # sports
            'sport_id' => __('custom.gyms.sports.id'),
            'sports' => __('custom.gyms.sports'),
            'sports.*' => __('custom.gyms.sports'),

            # attributes
            'attribute_id' => __('custom.gyms.attributes.id'),
            'attributes' => __('custom.gyms.attributes'),
            'attributes.*' => __('custom.gyms.attributes'),

            # images
            'images' => __('custom.gyms.images'),
            'images.*' => __('custom.gyms.images.*'),
        ];
    }
}

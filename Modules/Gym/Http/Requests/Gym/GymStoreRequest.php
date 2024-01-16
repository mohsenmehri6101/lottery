<?php

namespace Modules\Gym\Http\Requests\Gym;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\ReserveTemplate;
use Modules\Gym\Entities\Gym;

class GymStoreRequest extends FormRequest
{
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
            'images.*' => 'required|mimes:png,jpg,jpeg',

            # reserve template data
            'time_template.*' => 'nullable|array',
            'time_template.*.from' => 'required_with:time_template.*.to|date_format:H:i',
            'time_template.*.to' => 'required_with:time_template.*.from|date_format:H:i',
            'time_template.*.break_time' => 'required|numeric|min:1',
            'time_template.*.price' => 'required|numeric|min:0',
            'time_template.*.gender_acceptance' => "required|numeric|in:$status_gender_acceptances",
            'time_template.*.week_numbers' => "required|integer|in:1,2,3,4,5,6,7",
        ];
    }

}

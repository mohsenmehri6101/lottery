<?php

namespace Modules\Article\Http\Requests\ReserveTemplate;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\ReserveTemplate;

class ReserveTemplateMultipleUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $statuses_gender_acceptance = implode(',', ReserveTemplate::getStatusGenderAcceptance());
        $statuses_reserve_template = implode(',', ReserveTemplate::getStatus());

        return [
            'reserve_templates.*' => 'nullable|array',
            'reserve_templates.*.id' => 'nullable',
            'reserve_templates.*.from' => 'nullable',
            'reserve_templates.*.to' => 'nullable',
            'reserve_templates.*.article_id' => 'nullable|exists:articles,id',
            'reserve_templates.*.week_number' => 'nullable|numeric|min:1|max:7',
            'reserve_templates.*.price' => 'nullable',
            'reserve_templates.*.cod' => 'nullable|boolean',
            'reserve_templates.*.is_ball' => 'nullable|boolean',
            'reserve_templates.*.gender_acceptance' => "nullable|numeric|in:$statuses_gender_acceptance",
            'reserve_templates.*.discount' => 'nullable|numeric',
            'reserve_templates.*.status' => "nullable|numeric|in:$statuses_reserve_template",
        ];
    }

}

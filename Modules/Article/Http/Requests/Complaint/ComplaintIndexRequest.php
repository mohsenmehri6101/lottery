<?php

namespace Modules\Article\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\Complaint;
use function convert_withs_from_string_to_array;

class ComplaintIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Complaint::$relations_);
        $statuses_complaints = implode(',', Complaint::getStatus());

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:complaints,id',
            'user_id' => 'nullable|exists:users,id',
            'description' => 'nullable',
            'status' => "nullable|numeric|in:$statuses_complaints",
            'user_creator' => 'nullable|exists:users,id',
            'user_editor' => 'nullable|exists:users,id',
            'factor_id' => 'nullable|exists:factors,id',
            'article_id' => 'nullable|exists:articles,id',
            'reserve_id' => 'nullable|exists:reserves,id',
            'reserve_template_id' => 'nullable|exists:reserve_templates,id',
            'common_complaint_id' => 'nullable|exists:common_complaints,id',

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",

            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }
}

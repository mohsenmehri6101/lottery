<?php

namespace Modules\Faq\Http\Requests\Faq;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Faq\Entities\Faq;
use function convert_withs_from_string_to_array;

class FaqIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Faq::$relations_);
        $statuses = implode(',', Faq::getStatus());

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:factors,id',
            'question' => 'nullable|string',
            'answer' => 'nullable|string',
            'order' => 'nullable|numeric',
            'status' => "nullable|in:$statuses",
            'user_creator' => 'nullable|exists:users,id',
            'user_editor' => 'nullable|exists:users,id',
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }
}

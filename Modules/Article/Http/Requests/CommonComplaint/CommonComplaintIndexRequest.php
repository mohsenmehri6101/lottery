<?php

namespace Modules\Article\Http\Requests\CommonComplaint;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Article\Entities\CommonComplaint;
use function convert_withs_from_string_to_array;

class CommonComplaintIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', CommonComplaint::$relations_);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:common_complaints,id',
            'text' => 'nullable',
            'search' => 'nullable',

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}

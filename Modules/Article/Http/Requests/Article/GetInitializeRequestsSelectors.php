<?php

namespace Modules\Article\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class GetInitializeRequestsSelectors extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }
    public function rules(): array
    {
        $lists = ['articles','tags','categories','sports','attributes','keywords','cities','provinces','gender_acceptances'];
        $withs_allows = implode(',', $lists);
        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }
}

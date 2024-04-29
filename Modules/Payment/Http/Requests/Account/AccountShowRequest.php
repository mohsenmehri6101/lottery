<?php

namespace Modules\Payment\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Payment\Entities\Account;
use function convert_withs_from_string_to_array;

class AccountShowRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Account::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}

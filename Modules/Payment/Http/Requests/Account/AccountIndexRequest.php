<?php

namespace Modules\Payment\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Payment\Entities\Account;
use function convert_withs_from_string_to_array;

class AccountIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Account::$relations_);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',

            'id' => 'nullable|exists:accounts,id',
            'account_number' => 'nullable',
            'card_number' => 'nullable',
            'shaba_number' => 'nullable',
            'user_id' => 'nullable|exists:users,id',
            'bank_id' => 'nullable|exists:banks,id',
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

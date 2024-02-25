<?php

namespace Modules\Payment\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Payment\Entities\Transaction;
use function convert_withs_from_string_to_array;

class TransactionShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Transaction::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }
}

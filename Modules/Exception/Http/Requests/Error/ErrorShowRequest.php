<?php

namespace Modules\Exception\Http\Requests\Error;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Exception\Entities\Error;

class ErrorShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $relations_permissible = implode(',', Error::$relations_ ?? []);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$relations_permissible",
        ];
    }

}

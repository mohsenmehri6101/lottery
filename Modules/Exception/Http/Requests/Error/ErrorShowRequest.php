<?php

namespace Modules\Exception\Http\Requests\Error;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Exception\Entities\Error;

class ErrorShowRequest extends FormRequest
{
    public function rules(): array
    {
        $relations_permissible = implode(',', Error::$relations_ ?? []);
        $selects_allows = implode(',', (new Error)->getFillable());

        return [
            'selects' => 'nullable|array',
            'selects.*' => "nullable|string|in:$selects_allows",

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$relations_permissible",
        ];
    }

}

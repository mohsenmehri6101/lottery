<?php

namespace Modules\Exception\Http\Requests\Error;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Exception\Entities\Error;

class ErrorIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $relations_permissible = implode(',', Error::$relations_ ?? []);
        $selects_allows = implode(',', (new Error)->getFillable());


        return [
            'paginate' => 'nullable|boolean',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1',

            'id' => 'nullable|exists:errors,id',
            'url' => 'nullable',
            'status_code' => 'nullable|numeric',
            'exception' => 'nullable|string',
            'message' => 'nullable|string',
            'user_creator' => 'nullable|exists:users,id',

            'selects' => 'nullable|array',
            'selects.*' => "nullable|string|in:$selects_allows",

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$relations_permissible",

            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }
}

<?php

namespace Modules\Exception\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class ExceptionStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $list_status_code_allowable = trim(implode(',',array_keys(Response::$statusTexts)));

        return [
            'exception' => 'nullable|unique:exceptions,exception',
            'message' => 'nullable',
            'level' => 'nullable|numeric|min:1|max:10',
            'status_code' => "nullable|numeric|in:$list_status_code_allowable",
            'description' => 'nullable',
        ];
    }
}

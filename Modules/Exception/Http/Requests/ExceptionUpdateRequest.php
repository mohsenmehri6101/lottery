<?php

namespace Modules\Exception\Http\Requests;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class ExceptionUpdateRequest extends FormRequest
{
    use CustomFormRequestTrait;
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_update_unique();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function rules(): array
    {
        $list_status_code_allowable = trim(implode(',', array_keys(Response::$statusTexts)));

        return [
            'exception' => 'nullable|update_unique:exceptions,exception',
            'message' => 'nullable',
            'level' => 'nullable|numeric|min:1|max:10',
            'status_code' => "nullable|numeric|in:$list_status_code_allowable",
            'description' => 'nullable',
        ];
    }
}

<?php

namespace Modules\Gym\Http\Requests\Tag;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class TagUpdateRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_update_unique();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function rules(): array
    {
        return [
            'tag' => 'nullable|filled|update_unique:tags,tag',
            'type' => 'nullable',
        ];
    }

}

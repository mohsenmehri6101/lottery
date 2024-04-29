<?php

namespace Modules\Authentication\Http\Requests\User;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    use CustomFormRequestTrait;
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->set_validator_update_unique();
    }

    public function rules(): array
    {
        return [
            'username' => 'nullable',
            'name' => 'nullable',
            'family' => 'nullable',
            'father' => 'nullable',
            'national_code' => 'nullable',
            'birthday' => 'nullable',
            'gender' => 'nullable',
            'address' => 'nullable',
        ];
    }
}

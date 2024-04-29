<?php

namespace Modules\Authentication\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CheckProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|exists:users,id',
        ];
    }

    public function attributes(): array
    {
        return [
        ];
    }

}

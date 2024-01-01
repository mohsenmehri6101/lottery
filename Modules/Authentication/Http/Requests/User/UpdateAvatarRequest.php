<?php

namespace Modules\Authentication\Http\Requests\User;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAvatarRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function rules(): array
    {
        return [
            'avatar' => 'required|mimes:png,jpg,jpeg',
        ];
    }
}

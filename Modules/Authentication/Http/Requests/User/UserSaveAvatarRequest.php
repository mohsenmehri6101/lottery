<?php

namespace Modules\Authentication\Http\Requests\User;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserSaveAvatarRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function rules(): array
    {
        return [
            // todo should be enable mimes
            'avatar' => "required",
        ];
    }

    public function attributes(): array
    {
        return [
            'avatar' => trans('custom.users.fields.avatar'),
        ];
    }

}

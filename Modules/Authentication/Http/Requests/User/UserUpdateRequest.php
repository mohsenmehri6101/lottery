<?php

namespace Modules\Authentication\Http\Requests\User;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Authentication\Entities\User;
use Modules\Authentication\Entities\UserDetail;

class UserUpdateRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_likes();
        $this->set_validator_username();
        $this->set_validator_password();
        $this->set_validator_email();
        $this->set_validator_mobile();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function rules(): array
    {
        $statuses_gender = implode(',', UserDetail::getStatusGender());
        $statuses_user = implode(',', User::getStatusUser());

        return [
            'username' => 'nullable|string|filled|username|unique:users,username',
            'status' => "nullable|numeric|in:$statuses_user",
            'avatar' => 'nullable',
            'name' => 'nullable|string|filled',
            'family' => 'nullable|string|filled',
            'father' => 'nullable|string|filled',
            'national_code' => 'nullable|string|filled|unique:user_details,national_code',
            'birthday' => 'nullable',
            'gender' => "nullable|numeric|in:$statuses_gender",
            'address' => 'nullable|string|filled',
        ];
    }

    public function attributes(): array
    {
        return [
            'parent_code' => trans('custom.authentication.users.fields.parent_code'),
            'username' => trans('custom.authentication.users.fields.username'),
            'password' => trans('custom.authentication.users.fields.password'),
            'email' => trans('custom.authentication.users.fields.email'),
            'mobile' => trans('custom.authentication.users.fields.mobile'),
            'status' => trans('custom.authentication.users.fields.status'),
            'avatar' => trans('custom.authentication.users.fields.avatar'),
            'name' => trans('custom.authentication.user_details.fields.name'),
            'family' => trans('custom.authentication.user_details.fields.family'),
            'father' => trans('custom.authentication.user_details.fields.father'),
            'national_code' => trans('custom.authentication.user_details.fields.national_code'),
            'birthday' => trans('custom.authentication.user_details.fields.birthday'),
            'gender' => trans('custom.authentication.user_details.fields.gender'),
            'address' => trans('custom.authentication.user_details.fields.address'),
        ];
    }

}

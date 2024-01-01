<?php

namespace Modules\Authentication\Http\Requests\User;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Authentication\Entities\User;
use Modules\Authentication\Entities\UserDetail;

class UserIndexRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_likes();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $statuses_gender = implode(',', UserDetail::getStatusGender());
        $statuses_user = implode(',', User::getStatusUser());

        $fillables=(new User())->getFillable();
        $list_fillable=convert_array_to_string($fillables);

        $relations_permissible = implode(',', User::$relations_ ?? []);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',

            'search' => 'nullable|string',
            # users
            'id' => 'nullable',
            'code' => 'nullable',
            'parent_code' => 'nullable',
            'username' => 'nullable',
            'password' => 'nullable',
            'email' => 'nullable',
            'mobile' => 'nullable',
            'status' => "nullable|numeric|in:$statuses_user",
            'avatar' => 'nullable',
            'mobile_verified_at' => 'nullable',
            'email_verified_at' => 'nullable',
            'user_creator' => 'nullable',
            'user_editor' => 'nullable',
            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',

            'user_id' => 'nullable',
            'name' => 'nullable',
            'family' => 'nullable',
            'father' => 'nullable',
            'national_code' => 'nullable',
            'birthday' => 'nullable',
            'gender' => 'nullable',
            'address' => 'nullable',
            'users_details_user_creator' => 'nullable',
            'users_details_user_editor' => 'nullable',
            'users_details_created_at' => 'nullable',
            'users_details_updated_at' => 'nullable',
            'users_details_deleted_at' => 'nullable',

            'role_ids' => 'nullable|array',
            'role_ids.*' => 'required|filled|exists:roles,id',

            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$relations_permissible",

            'order_by' => "nullable|filled|in:$list_fillable",
            'direction_by' => "nullable|filled|in:asc,desc",
        ];
    }

    public function attributes(): array
    {
        return [
            'paginate' => trans('validation.attributes.paginate'),
            'per_page' => trans('validation.attributes.per_page'),
            'id' => trans('custom.authentication.users.fields.id'),
            'code' => trans('custom.authentication.users.fields.code'),
            'parent_code' => trans('custom.authentication.users.fields.parent_code'),
            'username' => trans('custom.authentication.users.fields.username'),
            'password' => trans('custom.authentication.users.fields.password'),
            'email' => trans('custom.authentication.users.fields.email'),
            'mobile' => trans('custom.authentication.users.fields.mobile'),
            'status' => trans('custom.authentication.users.fields.status'),
            'avatar' => trans('custom.authentication.users.fields.avatar'),
            'mobile_verified_at' => trans('custom.authentication.users.fields.mobile_verified_at'),
            'email_verified_at' => trans('custom.authentication.users.fields.email_verified_at'),
            'user_creator' => trans('custom.authentication.users.fields.user_creator'),
            'user_editor' => trans('custom.authentication.users.fields.user_editor'),
            'created_at' => trans('custom.authentication.users.fields.created_at'),
            'updated_at' => trans('custom.authentication.users.fields.updated_at'),
            'deleted_at' => trans('custom.authentication.users.fields.deleted_at'),

            'user_id' => trans('custom.authentication.user_details.fields.user_id'),
            'name' => trans('custom.authentication.user_details.fields.name'),
            'family' => trans('custom.authentication.user_details.fields.family'),
            'father' => trans('custom.authentication.user_details.fields.father'),
            'national_code' => trans('custom.authentication.user_details.fields.national_code'),
            'birthday' => trans('custom.authentication.user_details.fields.birthday'),
            'gender' => trans('custom.authentication.user_details.fields.gender'),
            'address' => trans('custom.authentication.user_details.fields.address'),
            'users_details_user_creator' => trans('custom.authentication.user_details.fields.user_creator'),
            'users_details_user_editor' => trans('custom.authentication.user_details.fields.user_editor'),
            'users_details_created_at' => trans('custom.authentication.user_details.fields.created_at'),
            'users_details_updated_at' => trans('custom.authentication.user_details.fields.updated_at'),
            'users_details_deleted_at' => trans('custom.authentication.user_details.fields.deleted_at'),
        ];
    }
}

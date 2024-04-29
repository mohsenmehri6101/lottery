<?php

namespace Modules\Authentication\Http\Requests;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_mobile();
        $this->set_validator_username();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function rules(): array
    {
        return [
            'username'=>'required|filled|string|username|unique:users,username',
            'name'=>'required|filled|string',
            'family'=>'required|filled|string',
            'mobile'=>'required|numeric|mobile|filled|unique:user_mobiles,mobile',
        ];
    }


    public function attributes(): array
    {
        return [
//            'username'=>'',
//            'name'=>'',
//            'family'=>'',
//            'mobile'=>'',
        ];
    }
}

<?php

namespace Modules\Authentication\Http\Requests;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class RegisterConfirmRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_mobile();
        $this->set_validator_otp_confirm();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    protected function prepareForValidation()
    {

    }

    public function rules(): array
    {
        return [
            'mobile' => 'required|numeric|mobile|filled',
            'otp' => 'required|numeric|filled|otp_confirm',
        ];
    }

    public function attributes(): array
    {
        return [
            'mobile' => trans(''),
            'otp_confirm' => trans(''),
        ];
    }
}

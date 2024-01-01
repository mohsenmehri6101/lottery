<?php

namespace Modules\Authentication\Http\Requests;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class OtpConfirmRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_mobile();
        $this->set_validator_otp_confirm();
        $this->set_validator_otp_check_expired_time();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }
    public function rules(): array
    {
        return [
            'mobile' => 'required|mobile|filled',
            'code' => 'required|filled|otp_confirm|otp_check_expired_time',
        ];
    }
}

<?php

namespace Modules\Authentication\Http\Requests;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
class RegisterResendCodeRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_mobile();
        $this->set_validator_check_allow_send_code();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }
    public function rules(): array
    {
        return [
            'mobile' => 'required|mobile|filled|check_allow_send_code',
        ];
    }
}

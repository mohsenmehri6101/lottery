<?php

namespace Modules\Gym\Http\Requests\QrCode;

use Illuminate\Foundation\Http\FormRequest;

class QrCodeStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => 'required|string|unique:qr_codes,url',
            'string-random' => 'required|string',
        ];
    }
}

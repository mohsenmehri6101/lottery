<?php

namespace Modules\Article\Http\Requests\QrCode;

use Illuminate\Foundation\Http\FormRequest;

class QrCodeUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => 'nullable|string',
            'string_random' => 'nullable|string',
        ];
    }
}

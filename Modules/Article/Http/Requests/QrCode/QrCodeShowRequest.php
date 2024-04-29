<?php

namespace Modules\Article\Http\Requests\QrCode;

use Illuminate\Foundation\Http\FormRequest;

class QrCodeShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'withs' => 'nullable|array',
            'withs.*' => 'nullable|string',
        ];
    }
}

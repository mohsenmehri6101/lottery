<?php

namespace Modules\ContactUs\Http\Requests\ContactUs;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable',
            'email' => 'nullable',
            'phone' => 'nullable',
            'text' => 'nullable',
            'status' => 'nullable',
        ];
    }

}

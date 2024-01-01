<?php

namespace Modules\ContactUs\Http\Requests\ContactUs;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'nullable|exists:contact_us,id',
        ];
    }

}

<?php

namespace Modules\ContactUs\Http\Requests\ContactUs;

use Illuminate\Foundation\Http\FormRequest;
class ContactUsStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable',
            'email' => 'nullable|string|filled|email',
            'phone' => 'nullable',
            'text' => 'nullable',
            'status' => 'nullable',
        ];
    }

}

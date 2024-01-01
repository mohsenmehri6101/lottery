<?php

namespace Modules\ContactUs\Http\Requests\ContactUs;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:contact_us,id',
            'name' => 'nullable',
            'email' => 'nullable',
            'phone' => 'nullable',
            'text' => 'nullable',
            'status' => 'nullable',
            'created_at' => 'nullable',
            'updated_at' => 'nullable',
        ];
    }

}

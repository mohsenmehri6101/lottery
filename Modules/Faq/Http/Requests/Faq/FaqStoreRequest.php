<?php

namespace Modules\Faq\Http\Requests\Faq;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Faq\Entities\Faq;

class FaqStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $statuses = implode(',', Faq::getStatus());

        return [
            'question' => 'required|filled|unique:faqs,question',
            'answer' => 'required|filled',
            'order' => 'nullable|numeric',
            'status' => "nullable|in:$statuses",
        ];
    }
}

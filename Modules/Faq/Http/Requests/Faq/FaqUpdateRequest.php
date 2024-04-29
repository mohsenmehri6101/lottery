<?php

namespace Modules\Faq\Http\Requests\Faq;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Faq\Entities\Faq;

class FaqUpdateRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_update_unique();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function rules(): array
    {
        $statuses = implode(',', Faq::getStatus());

        return [
            'question' => 'required_without:answer|filled|update_unique:faqs,question',
            'answer' => 'required_without:question|filled',
            'order' => 'nullable',
            'status' => "nullable|in:$statuses",
        ];
    }
}

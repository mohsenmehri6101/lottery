<?php

namespace Modules\Article\Http\Requests\CommonComplaint;

use Illuminate\Foundation\Http\FormRequest;

class CommonComplaintStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => 'required|filled',
        ];
    }
}

<?php

namespace Modules\Article\Http\Requests\CommonComplaint;

use Illuminate\Foundation\Http\FormRequest;

class CommonComplaintUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => 'required|filled',
        ];
    }
}

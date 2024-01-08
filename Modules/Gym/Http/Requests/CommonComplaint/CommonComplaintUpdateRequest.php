<?php

namespace Modules\Gym\Http\Requests\CommonComplaint;

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

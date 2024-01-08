<?php

namespace Modules\Gym\Http\Requests\CommonComplaint;

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

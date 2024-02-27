<?php

namespace Modules\Gym\Http\Requests\Gym;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Gym;

class GymToggleActivateRequest extends FormRequest
{
    public function rules(): array
    {
        $list_status_allowable = trim(implode(',', [Gym::status_active, Gym::status_disable]));
        return [
            'status' => "nullable|numeric|in:$list_status_allowable",
        ];
    }


}

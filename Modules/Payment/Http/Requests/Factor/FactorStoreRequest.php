<?php

namespace Modules\Payment\Http\Requests\Factor;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Payment\Entities\Factor;

class FactorStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $statuses = implode(',', Factor::getStatus());

        return [
            'reserve_id' => 'nullable|filled|exists:reserves,id',
            'reserve_ids' => 'nullable|array',
            'reserve_ids.*' => 'nullable|filled|exists:reserves,id',
            'description' => "nullable|string",
            'status' => "nullable|numeric|in:$statuses",
            'user_id' => 'nullable|exists:users,id',
            'gym_id' => 'nullable|exists:gyms,id',
        ];
    }

}

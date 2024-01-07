<?php

namespace Modules\Gym\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Complaint;

class ComplaintStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $statuses_complaints = implode(',', Complaint::getStatus());

        return [
            'user_id' => 'required|exists:users,id',
            'description' => 'required',
            'status' => "required|numeric|in:$statuses_complaints",
            'factor_id' => 'required|exists:factors,id',
            'gym_id' => 'required|exists:gyms,id',
            'reserve_id' => 'required|exists:reserves,id',
            'reserve_template_id' => 'required|exists:reserve_templates,id',
        ];
    }
}

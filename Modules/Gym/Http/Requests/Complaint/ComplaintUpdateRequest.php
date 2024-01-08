<?php

namespace Modules\Gym\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Complaint;

class ComplaintUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $statuses_complaints = implode(',', Complaint::getStatus());

        return [
            'user_id' => 'nullable|exists:users,id',
            'description' => 'nullable',
            'status' => "nullable|numeric|in:$statuses_complaints",
            'factor_id' => 'nullable|exists:factors,id',
            'gym_id' => 'nullable|exists:gyms,id',
            'reserve_id' => 'nullable|exists:reserves,id',
            'reserve_template_id' => 'nullable|exists:reserve_templates,id',
            'common_complaint_id' => 'nullable|exists:common_complaints,id',
        ];
    }

}

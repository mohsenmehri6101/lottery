<?php

namespace Modules\Gym\Http\Requests\Reserve;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Reserve;

class ReserveStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $statuses = implode(',', Reserve::getPaymentStatus());

        return [
            'reserve_template_id' => 'required|exists:reserve_templates,id',
            'gym_id' => 'nullable|exists:gyms,id',
            'user_id' => 'required|exists:users,id',
            'payment_status' => "nullable|numeric|in:$statuses",
            // todo check data bigger than today $date>= $today
            'dated_at' => 'required|unique:reserves,dated_at',
        ];
    }

    public function attributes(): array
    {
        return [
            'dated_at'=>'تاریخ ثبت',
        ];
    }
}

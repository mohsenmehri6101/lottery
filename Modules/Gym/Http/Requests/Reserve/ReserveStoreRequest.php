<?php

namespace Modules\Gym\Http\Requests\Reserve;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Payment\Entities\Payment;

class ReserveStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $statuses = implode(',', Payment::getStatusPayment());

        return [
            'reserve_template_id' => 'required|exists:reserve_templates,id',
            'gym_id' => 'nullable|exists:gyms,id',
            'user_id' => 'required|exists:users,id',
            // todo should be check any cant save this. and just change with trigger or ?!
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

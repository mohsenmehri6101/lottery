<?php

namespace Modules\Gym\Http\Requests\Reserve;

use Illuminate\Foundation\Http\FormRequest;

class ReserveStoreFactorLinkPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reserves' => 'nullable|array',
            'reserves.*' => 'nullable|array',
            'reserves.*.reserve_template_id' => 'required|exists:reserve_templates,id',
            'reserves.*.gym_id' => 'required|exists:gyms,id',
            // todo why you get user_id ? just give me one reason.
            // 'reserves.*.user_id' => 'nullable|exists:users,id',
            // todo should be implement validation
             'reserves.*.dated_at' => 'required',/* |unique:reserves,dated_at */
            // todo should be allow user can send user_id? infact from gym-manager if want set reserve from customers-special.
             'reserves.*.user_id' => 'nullable|numeric|exists:users,id',
             'reserves.*.want_ball' => 'nullable|numeric|in:0,1',
        ];
    }
}

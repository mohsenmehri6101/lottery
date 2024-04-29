<?php

namespace Modules\Article\Http\Requests\Reserve;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Payment\Entities\Payment;

class ReserveStoreRequest extends FormRequest
{

    protected function prepareForValidation(): void
    {
        if ($this->has('want_ball')) {
            $want_ball = $this->get('want_ball');
            $this->merge(['want_ball' => $want_ball ? 1 : 0]);
        }
    }

    public function rules(): array
    {
        $statuses = implode(',', Payment::getStatusPayment());

        return [
            'reserve_template_id' => 'required|exists:reserve_templates,id',
            'article_id' => 'nullable|exists:articles,id',
            'user_id' => 'required|exists:users,id',
            // todo should be check any cant save this. and just change with trigger or ?!
            'payment_status' => "nullable|numeric|in:$statuses",
            // todo check data bigger than today $date>= $today
            'dated_at' => 'required|unique:reserves,dated_at',
            'want_ball' => 'nullable|numeric|in:0,1',
        ];
    }

    public function attributes(): array
    {
        return [
            'dated_at'=>'تاریخ ثبت',
        ];
    }
}

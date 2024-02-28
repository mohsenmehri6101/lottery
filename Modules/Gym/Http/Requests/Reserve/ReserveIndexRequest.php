<?php

namespace Modules\Gym\Http\Requests\Reserve;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Reserve;
use Modules\Payment\Entities\Payment;
use function convert_withs_from_string_to_array;

class ReserveIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Reserve::$relations_);
        $statuses = implode(',', Payment::getStatusPayment());

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:categories,id',
            'reserve_template_id' => 'nullable|exists:reserve_templates,id',
            'user_id' => 'nullable|exists:users,id',
            'payment_status' => "nullable|numeric|in:$statuses",
            'user_creator' => 'nullable|exists:users,id',
            'user_editor' => 'nullable|exists:users,id',
            'dated_at' => 'nullable',
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }
}

<?php

namespace Modules\Payment\Http\Requests\Factor;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Payment\Entities\Factor;
use function convert_withs_from_string_to_array;

class FactorIndexRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', Factor::$relations_);
        $statuses = implode(',', Factor::getStatus());

        $fillables=(new Factor())->getFillable();
        $list_fillable=convert_array_to_string($fillables);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:factors,id',
            'reserve_id' => 'nullable|filled|exists:reserves,id',
            'reserve_ids' => 'nullable|array',
            'reserve_ids.*' => 'nullable|filled|exists:reserves,id',
            'code' => 'nullable',
            'total_price' => 'nullable',
            'status' => "nullable|numeric|in:$statuses",
            'user_id' => 'nullable|exists:users,id',
            'user_creator' => 'nullable|exists:users,id',
            'user_editor' => 'nullable|exists:users,id',
            'payment_id' => 'nullable|exists:payments,id',
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",

            'order_by' => "nullable|filled|in:$list_fillable",
            'direction_by' => "nullable|filled|in:asc,desc",

            'created_at' => 'nullable',
            'updated_at' => 'nullable',
            'deleted_at' => 'nullable',
        ];
    }

}

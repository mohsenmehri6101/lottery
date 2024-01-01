<?php

namespace Modules\Gym\Http\Requests\Reserve;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Reserve;

class ReserveUpdateRequest extends FormRequest
{
    use CustomFormRequestTrait;
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->set_validator_unique_deleted_at_null();
    }

    public function rules(): array
    {
        $statuses = implode(',', Reserve::getPaymentStatus());

        return [
            'reserve_template_id' => 'nullable|exists:reserve_templates,id',
            'user_id' => 'nullable|exists:users,id',
            'payment_status' => "nullable|numeric|in:$statuses",
            'dated_at' => 'nullable|unique_deleted_at_null:reserves,dated_at',
        ];
    }
}

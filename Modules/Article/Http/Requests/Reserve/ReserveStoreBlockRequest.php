<?php

namespace Modules\Article\Http\Requests\Reserve;

use Illuminate\Foundation\Http\FormRequest;

class ReserveStoreBlockRequest extends FormRequest
{
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function rules(): array
    {
        return [
            'reserve_template_id' => 'required|exists:reserve_templates,id',
            // todo you should be more think about he. sounds like unique not a good way.
            'dated_at' => 'required|unique:reserves,dated_at',
        ];
    }

}

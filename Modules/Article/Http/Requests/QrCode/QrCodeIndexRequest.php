<?php

namespace Modules\Article\Http\Requests\QrCode;

use Illuminate\Foundation\Http\FormRequest;

class QrCodeIndexRequest extends FormRequest
{

//    protected function prepareForValidation(): void
//    {
//        $this->merge([
//            'withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))
//        ]);
//    }

    public function rules(): array
    {
//        $withs_allows = implode(',', QrCode::$relations_);

        return [
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable',
            'page' => 'nullable',
            'id' => 'nullable|exists:qr_codes,id',
            'name' => 'nullable|string',
//            'withs' => 'nullable|array',
//            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}

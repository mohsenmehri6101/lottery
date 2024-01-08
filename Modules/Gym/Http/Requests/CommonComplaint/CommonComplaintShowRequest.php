<?php

namespace Modules\Gym\Http\Requests\CommonComplaint;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\CommonComplaint;
use function convert_withs_from_string_to_array;

class CommonComplaintShowRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['withs' => convert_withs_from_string_to_array(withs: $this->get(key: 'withs'))]);
    }

    public function rules(): array
    {
        $withs_allows = implode(',', CommonComplaint::$relations_);

        return [
            'withs' => 'nullable|array',
            'withs.*' => "nullable|string|in:$withs_allows",
        ];
    }

}

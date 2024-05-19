<?php

namespace Modules\Gym\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Gym\Entities\ReserveTemplate;

class UniqueCourseTemplateStore implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        list($gym_id, $from, $to, $week_number) = explode(',', $value);

        $reserve_exists = !ReserveTemplate::query()
            ->where('gym_id', $gym_id)
            ->where('from', $from)
            ->where('to', $to)
            ->where('week_number', $week_number)
            ->exists();

        if ($reserve_exists) {
            $fail('The :attribute must be uppercase.');
        }
    }

    public function message(): string
    {
        return 'The combination of first name and last name must be unique.';
    }

}

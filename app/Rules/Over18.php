<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class Over18 implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        $dob = Carbon::parse($value);
        $age = $dob->age;
        if ($age < 18) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'User is under 18. Please select a different date';
    }
}

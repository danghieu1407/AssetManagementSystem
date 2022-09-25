<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LatinName implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        return preg_match("/^[\w\s'.-]+$/", $value);
    }

    public function message()
    {
//        return 'The validation error message.';
    }
}

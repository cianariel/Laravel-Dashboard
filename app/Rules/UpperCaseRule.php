<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Rule for upper case checking
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Rules
 */
class UpperCaseRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     **/
    public function passes($attribute, $value) : bool
    {
        return mb_strtoupper($value) === $value;
    }

    /**
     * Get the validation error message.
     **/
    public function message() : string
    {
        return 'The :attribute must be entirely uppercase text';
    }
}
